<?php
/**
 * Feed Front Controller 
 */
class FeedController extends CoreController
{
    /**
     * Inject dependencies for the language file
     * 
     * @param  Container  $container The Container
     */
    public function setContainer(Container $container)
    {
        parent::setContainer($container);
        $this->Access = $container['Access'];
        $this->Request = $container['Request'];
        $this->languages   =  $container['Language'];
        $this->AuthManager = $container['AuthManager'];
        $this->Session = $container['Session'];
    }
    /**
     * returns view languages
     * 
     */
    public function alwaysAction()
    {
        parent::alwaysAction();
        $this->model = new FeedModel($this->Database);

        $array  = array();
        if (isset($this->languages['views'][strtolower($this->Request['controller'])][strtolower($this->Request['action'])]) && isset($this->languages['general']))
        {    
            $this->view->languages = array_merge_recursive(
                $this->languages['views'][strtolower($this->Request['controller'])][strtolower($this->Request['action'])],
                $this->languages['general']
            );
        }
        else
        {
            if (isset($this->languages['general']))
            {
                $this->view->languages = $this->languages['general'];    
            }    
        }    
    }    
    
    /**
     * Index Action
     * will list the feeds
     */
    public function indexAction()
    {
        $this->view->feeds = $this->model->findAll()->fetchAll();
    }

    /**
     * Detail action
     * 
     */
    public function detailAction()
    {
        if (!isset($this->Request['id']) || !is_numeric($this->Request['id']))
        {
            $this->Router->redirect('index');
        }    
        $this->model->id = $this->Request['id'];
        $this->model->checkin();
        if (!isset($this->model->id) || empty($this->model->id))
        {
            $this->Router->redirect('index');
        }    
        $this->view->feed = new stdClass();
        $this->view->feed->name  = $this->model->name;
        // get the rss feeds
        $this->getFeedInfos($this->view->feed, $this->model->url, $this->model->itemsNumber);
    }    

    /**
     * Returns a list of elements from an rss Feed
     * 
     * @param  mixed  $url         
     * @param  int    $itemsNumber 
     * @return array the rss items
     */
    private function getFeedInfos($feed, $url, $itemsNumber)
    {
        $rss = $this->getRSS($url, $itemsNumber);

        $feed->title = $rss->channel->title;
        $feed->description = $rss->channel->description;
        $feed->link = $rss->channel->link;
        $feed->lastUpdated = $rss->channel->lastBuildDate;
        $feed->image = $rss->channel->image;
        $feed->items = array();
        foreach ($rss->channel->item as $item) 
        {
            if ($i == $itemsNumber) break;
            $feed->items[] = $item;
            $i++;
        }
    }

    /**
     * gets a feed
     * 
     * @param  mixed  $url         
     * @param  int    $itemsNumber Optional, defaults to 5. 
     * @return SimpleXMLElement the element
     */
    private function getRSS($url, $itemsNumber = 5)
    {
        $client = new CurlClient();
        $client->setUrl($url);
        $client->setReturnTransfer();
        $client->setAsGet();
        return simplexml_load_string($client->call());
    } 

    /**
     * add Action
     * 
     */
    public function addAction()
    {    
        $this->model->bind($this->Request);
        $this->model->checkin();
        $this->view->form = new FeedForm('Feed');
        $this->view->form->initFromModel($this->model);
        // Handle eventual errors
        if (isset($this->Request['errors']) && is_array($this->Request['errors']))
        {
            $this->view->form->setErrors($this->Request['errors']);
        }
    }

    public function saveAction()
    {
        $this->model->bind($this->Request);
        $form = new FeedForm('Feed');
        $form->initFromModel($this->model);
        if ($form->validate())
        {
            if ($this->model->save())
            {
                $this->Router->redirect('index', 'Feed', $this->language['redirect']['element_saved'], CoreView::MESSAGE_TYPE_SUCCESS);
            }
            else
            {
                $this->Router->redirect('index', 'Feed', $this->language['redirect']['element_not_saved'], CoreView::MESSAGE_TYPE_ERROR);
            }
        }
        else
        {
            $params = array_merge(array('id' => $this->model->id, 'errors' => $form->getErrors()));
             $this->Router->redirect('add', 'Feed', $this->language['redirect']['element_not_saved'], CoreView::MESSAGE_TYPE_ERROR, $params);
        }
        exit();

    }

}    
