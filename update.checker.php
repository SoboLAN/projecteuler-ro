<?php
    define ('ADMIN_USER', 'eulerroadmin');
    define ('ADMIN_PASS', 'eulerropass9ijn');
    
    function display_form ()
    {
        $output = '<form name="login" action="update.checker.php" method="post">
                    <p>Username: <input type="text" name="username" size="30" maxlength="30" /></p>
                    <p>Password: <input type="password" name="password" size="30" maxlength="30" /></p>
                    <p><textarea name="content" rows="40" cols="120"></textarea></p>
                    <input type="submit" name="submit" value="Login" />
                    <input type="hidden" name="submitted" value="TRUE" />
                    </form>';
        
        return $output;
    }
    
    require ('header.php');
    
    echo '<div id="content">
        <h2>Update Checker</h2>
        <br />';
    
    if (! isset ($_POST['submitted']))
    {
        $form = display_form ();
        
        echo $form . '<div style="clear:both;"></div>
                    </div>';

        require ('footer.php');

        echo '</div>
                </body>
                </html>';
                
        exit (0);
    }
    
    if ($_POST['username'] !== ADMIN_USER OR $_POST['password'] !== ADMIN_PASS)
    {
        echo '<div style="text-align:center;" class="noprint">
                <p>Username sau parola gresite.</p>
                </div>
            </div>';

        require ('footer.php');

        echo '</div>
            </body>
            </html>';
        
        exit (0);
    }
    
    require_once ('include/db.class.php');
    require_once ('include/peproblem.class.php');
    
    function getAllProblems ()
    {
        $dbconn = new DBConn ();
        
        $r = $dbconn->executeQuery ("SELECT problem_id, UNIX_TIMESTAMP(last_main_update) AS 'last_main_update' FROM translations ORDER BY problem_id ASC");
        
        if (! $r)
        {
            echo 'DB ERROR';
            exit (-1);
        }
        
        $problems = array ();
        
        $row = $dbconn->nextRowAssoc ();
        
        while ($row !== FALSE)
        {
            $id = (int) $row['problem_id'];
            
            $new_problem = new PEProblem ($id, '1', ' ', ' ', ' ', ' ');    //except for ID, all fields are irrelevant here

            $new_problem->setLastMainUpdate ((int) $row['last_main_update']);

            $problems[] = $new_problem;
            
            $row = $dbconn->nextRowAssoc ();
        }
        
        return $problems;
    }
    
    $problems_input = explode ("\n", $_POST['content']);
    
    $all_problems = getAllProblems ();
    
    echo '<div style="clear:both;"></div>
        <br />
        <table class="grid" style="width:600px">
        <tr><th style="width:40px;"><strong><span style="color:#000;text-decoration:underline;">ID</span></strong></th><th><b>Update Timestamp on DB</b></th><th><b>Update Timestamp in Input</b></th><th><b>Status</b></th></tr>';

    foreach ($problems_input as $problem)
    {
        $elements = explode ('##', $problem);
        
        for ($i = 0; $i < count ($all_problems); $i++)
        {
            if ($all_problems[$i]->getProblemID () == $elements[0])
            {
                echo '
                    <tr><td style="height:30px;"><div style="text-align:center;"><b>' . $elements[0] . '</b></div></td>';
                echo '<td><div style="text-align:center;">' . $all_problems[$i]->getLastMainUpdate () . '</div></td>';
                echo '<td><div style="text-align:center;">' . $elements[3] . '</div></td>';
                
                if ((int) $elements[3] !== $all_problems[$i]->getLastMainUpdate())
                {
                    echo '<td><div style="text-align:center;">NOT OK</div></td></tr>';
                }
                else
                {
                    echo '<td><div style="text-align:center;">OK</div></td></tr>';
                }
            }
        }
    }
    
    echo '</table>
            <br />';
    
    echo '<div style="clear:both;"></div>
        </div>';
    
    require ('footer.php');
    
    echo '</div>
        </body>
        </html>';
?>