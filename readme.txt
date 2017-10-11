code from : drago
//https://www.sanwebe.com/assets/content-voting-system/
//https://www.phpjabbers.com/php--mysql-select-data-and-split-on-pages-php25.html

hijararhija u sqlu 
http://www.slideshare.net/billkarwin/models-for-hierarchical-data
http://karwin.blogspot.ba/2010/03/rendering-trees-with-closure-tables.html
http://salman-w.blogspot.ba/2012/08/php-adjacency-list-hierarchy-tree-traversal.html

show subcomment
http://stackoverflow.com/questions/18904298/show-form-onclick-inside-a-div

ajaq jquery json server response
https://scotch.io/tutorials/submitting-ajax-forms-with-jquery

js cookies
https://github.com/js-cookie/js-cookie

shorten text
http://viralpatel.net/blogs/dynamically-shortened-text-show-more-link-jquery/

header procitaj comment ima link za ispravjenu verziju
http://stackoverflow.com/questions/21037833/exclude-menu-item-from-the-collapse-of-bootstrap-3-navbar

http://bootstrap-live-customizer.com/

https://github.com/PHPMailer/PHPMailer/

mosaic tiles with bootstrap
http://www.bootply.com/vxJKAxwfPM

dubugiranje fb modula
https://developers.facebook.com/tools/debug/og/object/


mysql tabele FK

create table ventovi (id int(11) not null auto_increment
, ime varchar(25) NOT NULL
, poruka varchar(2550) NOT NULL
, reg_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
, PRIMARY KEY (id))
ENGINE=InnoDB COLLATE=utf8_unicode_ci;

create table comments (id int(11) not null auto_increment
, ime varchar(25) not null
, poruka varchar(2550) not null
, ventoviid int(11) not null
, reg_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
, PRIMARY KEY (id)
, CONSTRAINT comments_fk1 FOREIGN KEY (ventoviid) REFERENCES ventovi (id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COLLATE=utf8_unicode_ci;

CREATE TABLE `voting_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_content_id` int(11) not null,
  `vote_up` int(11) NOT NULL,
  `vote_down` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT votes_fk1 FOREIGN KEY (unique_content_id) REFERENCES ventovi (id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

create view totals as
select vote_up,vote_down,unique_content_id as sum
from voting_count

SELECT * FROM `totals` ORDER BY sum DESC 