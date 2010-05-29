;;
; Note : Code is released under the GNU LGPL
;
; Please do not change the header of this file 
;
; This library is free software; you can redistribute it and/or modify it under the terms of the GNU 
; Lesser General Public License as published by the Free Software Foundation; either version 2 of 
; the License, or (at your option) any later version.
;
; This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
;
; See the GNU Lesser General Public License for more details.
;;
;;
; File:        app.ini
; 
; @author      Anis BEREJEB
; @version     0.1
;;
;the general section will be viewed by all the mvc components (view, controller, model)
[general]
URL = http://simplemvc.berejeb.com/;


[database]
host = {mysqlHost};
user = {mysqlUser};
password = {mysqlPassword};
database = {mysqlDatabase};
type = mysql;

[template]
URL = http://simplemvc.berejeb.com/;

[controller]
list_default_number =  10;

[model]

[view]
autorender_template = 1;

[cache]
type = Array;
prefix = simplemvc;

