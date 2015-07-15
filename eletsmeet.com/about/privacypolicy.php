<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'cm_authfunc.inc.php');
$CONST_MODULE = 'privacypolicy';
$CONST_PAGEID = 'Privacy Policy';
require_once(INCLUDES_PATH.'cm_authorize.inc.php');
?>
<!DOCTYPE html>
<html lang="en">
    <!-- Head content Area -->
    <head>
        <?php include (INCLUDES_PATH.'head.php'); ?>
    </head>
    <!-- Head content Area -->

    <body>

        <!-- Navigation Bar, After Login Menu &  Product Logo -->
        <?php include (INCLUDES_PATH.'navigation.php'); ?>    
        <!-- Navigation Bar, After Login Menu &  Product Logo -->

        <div class="container">
            <div class="row">

                <div class="span7">
                    <h1>Privacy Policy</h1>
                </div>

                <!-- User setting option include start. -->
                <?php include (INCLUDES_PATH.'user_setting.php'); ?>    
                <!-- User setting option include start. -->

                <div class="span12"><hr>

                    <p>
                        You, the end user of the QUADRIDGE TECHNOLOGIES  Software Product/Service, ("You", includes a person and/or company and/or other legal entity, who is/are authorized by you, for whom applicable license fees has been paid and who supplied password, provided to you, also used in the form "your" where applicable) and Your privacy is very important to us. We at QUADRIDGE TECHNOLOGIES  Limited ("QUADRIDGE TECHNOLOGIES ") are committed to respecting your online privacy and recognize your need for appropriate protection and management of any personally identifiable information ("Personal Information") you share with us. QUADRIDGE TECHNOLOGIES  does not sell or rent your Personal Information to third parties for any of their purposes, whether it is marketing or otherwise.<br/>
                        <br/>
                        "Personal Information" means any information that may be used to identify an individual, including, but not limited to, a name, a date of birth, an email address or other related information.<br/>
                        <br/>
                        QUADRIDGE TECHNOLOGIES  strives to protect your Personal Information no matter where that Personal Information is collected, transferred, or retained, although legal requirements may vary from country to country, and there may be countries that may not require an "adequate" level of protection for your Personal Information.<br/>
                        <br/>
                        All the subsidiaries and affiliates of QUADRIDGE TECHNOLOGIES  operate under similar privacy practices as described in this Privacy Policy ("Privacy Policy"). However, this Privacy Policy does not apply to the practices of companies that QUADRIDGE TECHNOLOGIES  does not own or control, or to people that QUADRIDGE TECHNOLOGIES  does not employ or manage.<br/>
                        <br/>
                        Please read this Privacy Policy to learn more about the ways in which QUADRIDGE TECHNOLOGIES  uses and protects your Personal Information.<br/>
                        <br/>
                        <strong>1. COLLECTION OF INFORMATION</strong><br/>
                        <strong>1.1 GENERAL</strong><br/>
                        QUADRIDGE TECHNOLOGIES  collects information that is either anonymous or personally identifiable.<br/>
                        Anonymous information refers to data that cannot be tied back to a specific individual, such as sources from where you update yourself about the latest software and applications for your Mobile device, or Internet sites you use to download and buy new software for Mobile devices.<br/>
                        <br/>
                        Personally identifiable information refers to data with respect to who you are, such as your Name, Date of Birth, Gender, Country, and Email Id.<br/>
                        <br/>
                        <strong>1.2 CHILDREN</strong><br/>
                        QUADRIDGE TECHNOLOGIES  recognizes that children, including young teens, may not be able to make informed choices about personal information requested online. Accordingly, QUADRIDGE TECHNOLOGIES  does not target children or teenagers (younger than eighteen years of age) for collection of information online. QUADRIDGE TECHNOLOGIES  does not solicit or collect customer identifiable information targeted at children and teenagers under eighteen.<br/><br/>
                        QUADRIDGE TECHNOLOGIES  will encourage children to seek the consent of their parents before providing any information about themselves or their households to anyone on the Internet. QUADRIDGE TECHNOLOGIES  encourages parents to take an active role to protect the privacy and security of their children and to prevent the inappropriate use of information about their children.<br/>
                        <br/>
                        <strong>2. USE OF INFORMATION</strong><br/>
                        <strong>2.1 USE OF PERSONAL INFORMATION BY QUADRIDGE TECHNOLOGIES</strong><br/>
                        In general, you can visit this web site without telling us who you are or revealing any Personal Information about yourself. However, most areas of this web site require registration to access. We collect this information to better understand your needs and provide you with services that are valuable to you. If you choose not to provide the information we request, you can still visit much of this web site.<br/>
                        <br/>
                        As a condition to being allowed to download software from this web site, you may be asked for your Personal Information to enable QUADRIDGE TECHNOLOGIES  and/or one of their affiliates, representatives or partners, to contact you from time to time by either email, postal mail, telephone and/or facsimile; to provide you with the information about any software downloaded from our web site and/or other products and/or services offered by QUADRIDGE TECHNOLOGIES  or its affiliates or partners that we or they think may be of interest to you; to resolve disputes; to troubleshoot problems; to measure consumer interest in our products and services; to customize your experience; or to detect and protect us against error, fraud and other criminal activity.<br/>
                        <br/>
                        You agree that QUADRIDGE TECHNOLOGIES  may use Personal Information about you to improve our marketing and promotional efforts, to analyze site usage, improve our content and product offerings, and customize the web site's content, layout, and services. These uses improve the web site and better tailor it to meet your needs, so as to provide you with a smooth, efficient, safe and customized experience while using the web site.<br/>
                        <br/>
                        <strong>2.2 DISCLOSURE OF PERSONAL INFORMATION</strong><br/>
                        As a matter of policy, QUADRIDGE TECHNOLOGIES  does not sell or rent any of your Personal Information to third parties for their marketing, research or any other purposes. However in certain circumstances, it is possible that Personal Information may be subject to disclosure pursuant to laws, judicial or other government subpoenas, warrants, or orders.<br/>
                        <br/>
                        <strong>3. CONFIDENTIALITY AND SECURITY</strong><br/>
                        QUADRIDGE TECHNOLOGIES  will limit access to your Personal Information to its employees who reasonably need to come into contact with that information to provide products or services to you or in order to perform their responsibilities as employees. QUADRIDGE TECHNOLOGIES  has physical, electronic, and procedural safeguards that comply with relevant regulations to protect Personal Information about you.<br/>
                        <br/>
                        <strong>4. ACCESS AND ACCURACY</strong><br/>
                        To the extent that you do provide us with Personal Information, QUADRIDGE TECHNOLOGIES  wishes to maintain accurate Personal Information. Where QUADRIDGE TECHNOLOGIES  collects Personal Information from you on the Web, its goal is to provide a means of contacting QUADRIDGE TECHNOLOGIES  should you need to update or correct that Information. If for any reason those means are unavailable or inaccessible, you may send updates and corrections about your Personal Information to <a href="mailto:<?php echo $CONST_ENQUIRY_EID; ?>"><?php echo $CONST_ENQUIRY_EID; ?></a> and we will make reasonable efforts to incorporate the changes in your Personal Information that we hold as soon as practicable.<br/>
                        <br/>
                        <strong>5. CHANGES TO THIS PRIVACY POLICY</strong><br/>
                        QUADRIDGE TECHNOLOGIES  may update this policy. We will notify you about significant changes in the way we treat Personal Information by sending a notice to the primary email address specified in your Personal Information or by placing a prominent notice on our web site.<br/>
                        <br/>
                        <strong>6. QUESTIONS AND SUGGESTIONS</strong><br/>
                        If you are a consumer with concerns about the QUADRIDGE TECHNOLOGIES  online privacy policy or its implementation you may contact us at <strong>+91 22 6671 3517</strong> or email us at <a href="mailto:<?php echo $CONST_ENQUIRY_EID; ?>"><?php echo $CONST_ENQUIRY_EID; ?></a>.<br/>
                        <br/>
                        <strong>7. YOUR CONSENT</strong><br/>
                        By using this web site and providing your Personal Information, you consent to the terms of our Online Privacy Policy and to QUADRIDGE TECHNOLOGIES 's processing of Personal Information for the purposes given above as well as those explained where QUADRIDGE TECHNOLOGIES  collects Personal Information on the web site<br>
                        &nbsp;</p>

                </div>

            </div>
        </div>
        <!-- Footer content Area -->
        <?php include (INCLUDES_PATH.'footer.php'); ?>
        <!-- Footer content Area -->

        <!-- /container -->

        <!-- java script  -->
        <?php include (INCLUDES_PATH.'jsinclude.php'); ?>
        <!-- java script  -->

    </body></html>