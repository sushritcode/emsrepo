<?php

require_once('../includes/global.inc.php');
require_once(CLASSES_PATH . 'error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(INCLUDES_PATH . 'cm_authfunc.inc.php');
$CONST_MODULE = 'contact';
$CONST_PAGEID = 'Contact List';
require_once(INCLUDES_PATH . 'cm_authorize.inc.php');
require_once(INCLUDES_PATH . 'contact_function.inc.php');

try
{
    try
    {
        $arrContactList = getContactListbyType($strCK_user_id, PERSONAL_CONTACT_TYPE, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("contactlist.php : getContactListbyType : Error in populating ContactDetailsList." . $a->getMessage(), 541);
    }
    $strErrorMsg = trim(urldecode($_REQUEST['msg']));
}
catch (Exception $e)
{
    $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), true);
}
?>
<!DOCTYPE html>
<html lang="en">
    <!-- Head content Area -->
    <head>
    <?php include (INCLUDES_PATH . 'head.php'); ?>
    </head>
    <!-- Head content Area -->

    <body>

        <!-- Navigation Bar, After Login Menu &  Product Logo -->
        <?php include (INCLUDES_PATH . 'navigation.php'); ?>    
        <!-- Navigation Bar, After Login Menu &  Product Logo -->

        <!-- Main content Area -->
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->

            <!-- Middle content Area -->
            <div class="row">
                <div class="span12">
                    <h2>My Contacts</h2>
                </div>

                <div class="cB"></div>

                <div class="span12 "><hr>
                
                <div class="fR mB10">
                    <a class="btn btn-primary" href="<?php echo $SITE_ROOT; ?>contacts/addcontact.php"><i class='icon-white icon-plus-sign'></i>&nbsp;Add Contact</a>&nbsp;
                    <a class="btn btn-primary" href="<?php echo $SITE_ROOT;  ?>contacts/import.php"><i class='icon-white icon-upload'></i>&nbsp;Import Contact</a>
                </div>

                <div class="cB"></div>

                    <?php
                    if (strlen(trim($strErrorMsg)) > 0)
                    {
                        echo "<div class='alert alert-success'>$strErrorMsg</div>";
                    }
                    ?>
                    <div>
                    <?php
                    if (empty($arrContactList))
                    {
                        echo "<div class='alert alert-info'>No contacts available.</div>";
                    }
                    else
                    {
                     ?>
                            <div class="mB20"><h4>Total Contacts&nbsp;:&nbsp;<?php echo count($arrContactList);?></h4></div>
                            <div style="overflow:auto; height: 500px;">
                            <table class="tblz01" width="100%" id="contactResults">
                                <thead>
                                    <tr class="thead">
                                        <td width="10%">Nick Name</td>
                                        <td width="15%">First Name</td>
                                        <td width="15%">Last Name</td>
                                        <td width="20%">Email Address</td>
                                        <td width="10%">Mobile</td>
                                        <td width="15%">Group</td>
                                        <td width="10%" align="center">Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($intCntr = 0; $intCntr < sizeof($arrContactList); $intCntr++)
                                    {
                                        $contId = $arrContactList[$intCntr]['personal_contact_id'];
                                        $contName = $arrContactList[$intCntr]['contact_nick_name'];
                                        ?>
                                        <tr>
                                            <td><?php echo $arrContactList[$intCntr]['contact_nick_name']; ?></td>
                                            <td><?php echo $arrContactList[$intCntr]['contact_first_name']; ?></td>
                                            <td><?php echo $arrContactList[$intCntr]['contact_last_name']; ?></td>
                                            <td><?php echo $arrContactList[$intCntr]['contact_email_address']; ?></td>
                                            <?php if ($arrContactList[$intCntr]['contact_idd_code'] != NULL && $arrContactList[$intCntr]['contact_mobile_number'] != NULL)
                                            { ?>
                                                <td><?php echo "+".$arrContactList[$intCntr]['contact_idd_code']."-".$arrContactList[$intCntr]['contact_mobile_number']; ?></td>
                                            <?php }
                                            else
                                            { ?>
                                                <td><?php echo '--'; ?></td>
                                            <?php } ?>
                                            <td><?php echo $arrContactList[$intCntr]['contact_group_name']; ?></td>
                                            <form name="frmEditContact_<?php echo $contId; ?>" method="post" action="editcontact.php">
                                                <td align="center">
                                                    <div><span class="label label-success" style="cursor:pointer" onclick='javascript:EditNow("<?php echo $contId; ?>", "<?php echo $contName; ?>")'>Edit</span>
                                                    <span class="label label-warning" style="cursor:pointer" onclick='javascript:DeleteNow("<?php echo $contId; ?>", "<?php echo $contName; ?>")'>Delete</span></div>
                                                </td>
                                                <input type="hidden" name="contId" value="<?php echo $contId; ?>">
                                            </form>
                                        </tr>
                                    <?php } ?>
                                            <form name='frmContactList' method="post">
                                                <input type='hidden' name='txtFormName' value='deletecontact'>
                                                <input type='hidden' name='txtContactId'>
                                                <input type='hidden' name='txtContactName'>
                                            </form>
                                    </tbody>
                                </table>
                                </div>
                            
                                <?php } ?>
                                <div class="pagination pagination-centered" id="pageNavPositionContacts"></div> 
                            </div>

                        </div>

                    </div>
                    <!-- Middle content Area -->
                </div>
                <!-- Main content Area -->

                <!-- Footer content Area -->
                <?php include (INCLUDES_PATH . 'footer.php'); ?>
                <!-- Footer content Area -->

                <!-- java script  -->
                <?php include (INCLUDES_PATH . 'jsinclude.php'); ?>
                <!-- java script  -->

                <!-- java script  1-->
                <script src="<?php echo JS_PATH; ?>common.js"></script>
                <script src="<?php echo JS_PATH; ?>contacts.js"></script>
                <script src="<?php echo JS_PATH; ?>paging.js"></script>
                <!-- java script  1-->

                <script type="text/javascript">
                        var contactlist = '<?php echo $arrContactList; ?>';
                        if (contactlist != '')
                        {
                            var pagerContactList = new Pager('contactResults', 10, 'con');
                            pagerContactList.init();
                            pagerContactList.showPageNav('pagerContactList', 'pageNavPositionContacts');
                            pagerContactList.showPage(1);
                        }
                </script>
    </body>
</html>
