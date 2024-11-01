<?php

add_filter( 'gettext', 'change_publish_button', 10, 2 );

function change_publish_button( $translation, $text ) {

if ( $text == 'Publish' )
    return 'Save';

return $translation;
}