        <div id="fullpage">
            <h1 style="background-color: #4ba7b0;"><?php echo $this->title; ?></h1>
            <div id="fulltext">
                <?php
                $display_error = true;
                foreach($this->news as $record) {
                    $display_error = false;
                    echo '<h2>'.$record->title.'</h2>';
                    echo '<p><i>'.$record->date.':</i> '.$this->intro($record->content);
                    echo ' '.$this->action('news/view/'.$record->id,'TEXT_CONTINUE_READING');
                    echo '</p>';
                }
                if ($display_error) {
                    echo '<p>'.$this->getText('TEXT_NONEWS').'</p>';
                } else {
                    if ($this->max_page > 1) {
                        echo '<div id="forandback">';
                        if ($this->page > 1) {
                            echo $this->action('news/overview/'.($this->page - 1),'TEXT_BACK');
                        }
                        if (($this->page!=1)&&($this->page!=$this->max_page)) {
                            echo ' | ';
                        }
                        if ($this->page < $this->max_page) {
                            echo $this->action('news/overview/'.($this->page + 1),'TEXT_FORWARD');
                        }
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>