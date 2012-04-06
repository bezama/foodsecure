<?php


    class WelcomeView extends View
    {
        protected function renderContent()
        {
            return <<<END
    <br/>
    <br/>
    <br/>
    <div align='center'>
    <h2>
    Welcome to fosa. Ask your administrator to turn on the dashboard for you.
    </h2>
    </div>
END;
        }
    }
?>
