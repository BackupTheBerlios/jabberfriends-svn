<?php
class JForg_Model_News extends Solar_Sql_Model {
    protected function _setup() {
        $this->_table_name = 'news';
        $this->_table_cols = array(
            'id' => array(
                'type' => 'int',
                'required' => true,
                'primary' => true,
                'autoinc' => true,
            ),
            'language' => array(
                'type' => 'varchar',
                'size' => 7,
            ),
            'date' => array(
                'type' => 'timestamp'
            ),
            'title' => array(
                'type' => 'varchar',
                'size' => 100,
            ),
            'content' => array(
                'type' => 'clob'
            )
        );
        $this->_order = 'date DESC';
    }
}
?>