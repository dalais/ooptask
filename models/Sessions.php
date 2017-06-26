<?php

class Sessions extends ActiveRecord\Model
{
    public static $table_name = 'sessions';

    public static $primary_key = 'sid';
}