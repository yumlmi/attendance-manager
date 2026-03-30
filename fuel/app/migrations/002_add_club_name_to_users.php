<?php

namespace Fuel\Migrations;

class Add_club_name_to_users
{
    public function up()
    {
        \DBUtil::add_fields('users', array(
            'club_name' => array(
                'constraint' => 50,
                'type' => 'varchar',
                'null' => true,
                'default' => null,
                'after' => 'mail',
            ),
        ));
    }

    public function down()
    {
        \DBUtil::drop_fields('users', array('club_name'));
    }
}
