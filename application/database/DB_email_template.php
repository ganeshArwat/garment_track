<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <title>UVF Database Error Mail Template</title>
        <style>
            #border
            {
                border:1px solid #C10202;
            }

            h1 {
                font-weight:		normal;
                font-size:			14px;
                color:				#990000;
                margin: 			0 0 4px 0;
            }
        </style>
    </head>
    <body>
        <table width="80%"  border="0" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF" class="text1" id="border">
            <tr align="left" valign="top">
                <td colspan="3" bgcolor="#C10202" height="1"></td>
            </tr>
            <tr align="left" valign="top">
                <td colspan="3" align="center">DataBase Error Details</td>
            </tr>
            <tr>
                <td colspan="3" bgcolor="#C10202" height="1"></td>
            </tr>
            <tr>
                <td width="78%" colspan="3" align="left" valign="top" style="border-top-style:none; padding-left: 25px; padding-right:10px;">
                    <br>
                    <span class="text">
                        Dear <strong><?php echo ERROREMAIL_NAME ?></strong>, <br /><br />
                        <?php echo $heading; ?> for <strong><?php echo WEBSITE_NAME ?></strong>. Details are as follows:<br />
                    </span>
                </td>
            </tr>
            <tr>
                <td valign="top" colspan="3">
                    <table border="0" cellpadding="2" cellspacing="1"  width="100%" style="padding:10px; font-size:11px; font-family: Verdana, Arial, Helvetica, sans-serif;">
                        <tr><td bgcolor="#BDAF1A"><strong style="color:#FFFFFF;">Steps User Need to take in Order to Fix the Error:</strong></td></tr>
                        <tr><td>1. Execute the Query in PHP MyAdmin.<br/></td></tr>
                        <tr><td>2. Apply necessary updates to Query and fix the issue.<br/></td></tr>
                        <tr><td>3. Check the file path (in email template) and update the query.<br/></td></tr>
                        <tr><td>4. Test the Form By adding Test data & Delete it After Testing.<br/></td></tr>
                        <tr><td>5. Send the UPdates to ALL stakeholders (Check Projects).<br/></td></tr>
                        <tr><td><i>Please check the Domain IP address and IP address in Error Email Template <br/> Check Error generated Date and Time.</i><br/></td></tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td valign="top" colspan="3">
                    <table border="0" cellpadding="2" cellspacing="1"  width="100%" style="padding:10px; font-size:11px; font-family: Verdana, Arial, Helvetica, sans-serif;">
                        <tr><td bgcolor="#C10202"><strong style="color:#FFFFFF;">Error in Mysql Query :</strong></td></tr>
                        <tr><td style="font-size:13px;font-weight:bold;"><?php echo '<pre>';
                        print_r($message);
                        echo '</pre>'; ?></td></tr>
                        <tr><td bgcolor="#C10202"><strong style="color:#FFFFFF;">UVF Framework File Related Information:</strong></td></tr>
                        <tr><td><strong style="color:#000000;">Page</strong></td></tr>
                        <tr><td><?php echo "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] . '<hr/>' ?></td></tr>
                        <tr><td ><strong style="color:#000000;">URL</strong></td></tr>
                        <tr><td><?php echo $_SERVER["REQUEST_URI"] . '<hr/>'; ?></td></tr>
                        <tr><td><strong style="color:#000000;">Parent Controller Name</strong></td></tr>
                        <tr><td><?php echo $obj->router->class . '.php<hr/>'; ?></td></tr>
                        <tr><td ><strong style="color:#000000;">Function Name</strong></td></tr>
                        <tr><td><?php echo $obj->router->method . '<hr/>'; ?></td></tr>
                        <tr><td ><strong style="color:#000000;">Directory Path</strong></td></tr>
                        <tr><td><?php echo $obj->router->directory . '<hr/>'; ?></td></tr>
                        <tr><td></td></tr>
                        <tr><td bgcolor="#C10202"><strong style="color:#FFFFFF;">Database Details</strong></td></tr>
                        <tr><td ><strong style="color:#000000;">Database Name</strong></td></tr>
                        <tr><td><?php echo $obj->db->database . '<hr/>'; ?></td></tr>
                        <tr><td ><strong style="color:#000000;">Host Name</strong></td></tr>
                        <tr><td><?php echo $obj->db->hostname . '<hr/>'; ?></td></tr>
                        <tr><td ><strong style="color:#000000;">MySql BenchMarking</strong></td></tr>
                        <tr><td><?php echo $obj->db->benchmark . '<hr/>'; ?></td></tr>
                        <tr><td bgcolor="#C10202"><strong style="color:#FFFFFF;">All MySql Queries:</strong></td></tr>
                        <tr><td><?php
                                if (isset($obj->db->queries) && is_array($obj->db->queries)) {
                                    $all_queries = '';
                                    foreach ($obj->db->queries as $key => $qry_val) {
                                        if (!preg_match('/ci_sessions/i', $qry_val)) {
                                            ?>
                                            <table border="0" cellpadding="0" cellspacing="1" width="" style="font-size:11px; font-family: Verdana, Arial, Helvetica, sans-serif;">
                                                <tr><td width="150" valign="top">Query :</td><td><?php echo $qry_val; ?></td></tr>
                                                <tr><td valign="top">Execution Time :</td><td><strong><?php echo $obj->db->query_times[$key]; ?></strong></td> </tr>
                                            </table>  <hr/>              
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
            <tr>
                <td valign="top" colspan="3">
                    <table border="0" cellpadding="2" cellspacing="1"  width="100%" style="padding:10px; font-size:11px; font-family: Verdana, Arial, Helvetica, sans-serif;">
                        <tr><td bgcolor="#C10202"><strong style="color:#FFFFFF;">POST VALUES</strong></td></tr>
                        <tr><td><?php echo "<pre>";
                                print_r($_POST);
                                echo "</pre>"; ?></td></tr>
                        <tr><td bgcolor="#C10202"><strong style="color:#FFFFFF;">GET VALUES</strong></td></tr>
                        <tr><td><?php echo "<pre>";
                                print_r($_GET);
                                echo "</pre>"; ?></td></tr>
                        <tr><td bgcolor="#C10202"><strong style="color:#FFFFFF;">Files</strong></td></tr>
                        <tr><td><?php echo "<pre>";
                                print_r($_FILES);
                                echo "</pre>"; ?></td></tr>
                        <tr><td bgcolor="#C10202"><strong style="color:#FFFFFF;">SERVER VALUES</strong></td></tr>
                        <tr><td><?php
                                $ser_arr = array();
                                $ser_arr['SERVER_SIGNATURE'] = $_SERVER['SERVER_SIGNATURE'];
                                $ser_arr['SERVER_NAME'] = $_SERVER['SERVER_NAME'];
                                $ser_arr['SERVER_ADDR'] = $_SERVER['SERVER_ADDR'];
                                $ser_arr['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
                                $ser_arr['REQUEST_TIME'] = $_SERVER['REQUEST_TIME'];
                                $ser_arr['REMOTE_PORT'] = $_SERVER['REMOTE_PORT'];
                                $ser_arr['REFERRER'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
                                $ser_arr['REQUEST_DATE'] = '<strong>' . date('d-F-Y H:i:s') . '</strong>';
                                echo "<pre>";
                                print_r($ser_arr);
                                echo "</pre>";
                                ?>
                            </td>
                        </tr>
                        <tr><td bgcolor="#C10202"><strong style="color:#FFFFFF;">Function Path [== debug_backtrace(); ==]</strong></td></tr>
                        <tr><td><?php
                                $backtrace = array_reverse(debug_backtrace());
                                if (is_array($backtrace) && count($backtrace) > 0) {
                                    foreach ($backtrace as $key) {
                                        if (isset($key['file']) && !strstr($key['file'], 'system')) {
                                            ?>
                                            <table border="0" cellpadding="0" cellspacing="1" width="" style="font-size:11px; font-family: Verdana, Arial, Helvetica, sans-serif;">
                                                <tr><td width="150" valign="top">Filename :</td><td><?php if (isset($key['file'])) echo $key['file'];
                                            else echo ''; ?></td></tr>
                                                <tr><td valign="top">Function Name :</td><td><strong><?php if (isset($key['function'])) echo $key['function'];
                                            else echo ''; ?></strong></td> </tr>
                                                <tr><td valign="top">Line Number :</td><td><?php if (isset($key['line'])) echo $key['line'];
                                            else echo ''; ?></td></tr>
                                                <tr><td valign="top">Class :</td><td><?php if (isset($key['class'])) echo $key['class'];
                                else echo ''; ?></td></tr>
                                                <tr><td valign="top">Call Type</td> <td><?php if (isset($key['type'])) echo $key['type'];
                                else echo ''; ?></td> </tr>
                                                <tr><td><br /></td> <td><br /></td> </tr>
                                            </table>                            
            <?php
        }
    }//END OF foreach($backtrace as $key)
}//END OF if(is_array($backtrace) && count($backtrace)>0)
?>
                                <br/>
                                Type: The current call type. If a method call, "->" is returned. If a static method call, "::" is returned. If a function call, nothing is returned<br /><br />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr align="left" valign="top">
            </tr>
        </table>
    </body>
</html>


