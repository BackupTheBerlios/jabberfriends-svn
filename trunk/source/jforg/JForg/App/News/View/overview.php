        <div id="fullpage">
            <h1 style="background-color: #4ba7b0;"><?php echo $this->title; ?></h1>
            <div id="fulltext">
                <?php
                foreach($this->news as $record) {
                    echo '<h2>'.$record->title.'</h2>';
                    echo '<p>'.$record->content.'</p>';
                }
                if ($this->page > 1) {
                    echo $this->action('news/overview/'.($this->page - 1),'TEXT_BACK');
                }
                if (($this->page!=1)&&($this->page!=$this->max_page)) {
                    echo ' | ';
                }
                if ($this->page < $this->max_page) {
                    echo $this->action('news/overview/'.($this->page + 1),'TEXT_FORWARD');
                }
                ?>
            </div>
        </div>