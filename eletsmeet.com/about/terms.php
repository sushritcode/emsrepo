<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'cm_authfunc.inc.php');
$CONST_MODULE = 'terms';
$CONST_PAGEID = 'Terms of Service';
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
                    <h1>Terms of Service</h1>
                </div>

                <!-- User setting option include start. -->
                <?php include (INCLUDES_PATH.'user_setting.php'); ?>    
                <!-- User setting option include start. -->

                <div class="span12"><hr>

                    <p>
                        Please carefully read the following terms of a legal agreement (hereafter &quot;Terms of Service&quot; or &quot;TOS&quot;) between You, the end user of the QUADRIDGE TECHNOLOGIES  Software Product/Service, ("You", includes a person and/or an individual entity, also used in the form &quot;your&quot; where applicable) and QUADRIDGE TECHNOLOGIES. (&quot;QUADRIDGE TECHNOLOGIES &quot;) with respect to your access and use of the Q.CONFERENCE (&quot;Service&quot;). Use of the service is subject to terms and conditions set forth herein. Carefully read this TOS before using the Service. Use of Service indicates your complete and unconditional acceptance of the terms and conditions set forth in this TOS.<br/><br/>

                        <strong>1. ACCEPTANCE OF TOS AND SUBSCRIPTION</strong><br/>

                        <strong>1.1</strong> By accessing, and/or using the Service, you acknowledge that you have read, understood, and agree, to be bound by the TOS, Privacy Policy, EULA and any other Additional Terms which apply to Your use of the Service and to comply with all applicable laws and regulations. Each time you use the Service, you reaffirm your acceptance of the then-current TOS. If you do not wish to be bound by these TOS, you may discontinue using the Service.
                        <br/><br/>
                        <strong>1.2</strong> If you obtain more than one Service from QUADRIDGE TECHNOLOGIES , the TOS shall be applicable in respect of all the Services ("Services&quot;) and your acceptance of the TOS at the time of availing the Service for the first time shall be deemed to be acceptance of TOS for the Services availed subsequently.
                        <br/><br/>
                        <strong>1.3</strong> QUADRIDGE TECHNOLOGIES  is not obligated to provide updates or improvements to the Service. However, if QUADRIDGE TECHNOLOGIES , in its sole discretion, updates or improves the Service, the TOS shall apply to such updates and improvements unless expressly noted otherwise.
                        <br/><br/>

                        <strong>2. MODIFICATIONS OR UPDATES TO TOS</strong><br/>
                        <strong>2.1</strong> QUADRIDGE TECHNOLOGIES  may change the TOS at any time and such changes shall be effective immediately. You are responsible for regularly reviewing the TOS. The most recent version of the TOS can be found at Q.CONFERENCE website/wapsite/application. Your continued use of the Service affirms your agreement to the TOS and any changes.
                        <br/><br/>

                        <strong>3. REQUIREMENTS FOR REGISTRATION OR USE OF THE SERVICE</strong><br/>
                        <strong>3.1</strong> The Services are intended for general audiences. You represent and warrant that you have adequate legal capacity to enter into binding agreements such as this TOS. The Services may require the user to register and provide information to QUADRIDGE TECHNOLOGIES , such as name, date of birth, gender, e-mail address, Banking and payment information i.e. credit card information, account number (the "Registration Information"). If you register for any Service, you agree to provide accurate and complete Registration Information and you agree to keep such information current.
                        <br/><br/>
                        <strong>3.2</strong> If you suspect an error in the Registration Information supplied to QUADRIDGE TECHNOLOGIES , you shall immediately make ‘best efforts’ to correct the error wherever possible or inform QUADRIDGE TECHNOLOGIES  in writing. QUADRIDGE TECHNOLOGIES  is not responsible for any loss or damage caused to you due to non-compliance with this clause.
                        <br/><br/>

                        <strong>4. PAYMENT TERMS</strong><br/>
                        <strong>4.1</strong>  Subscription: To use the Service You need a subscription, which is purchased by You from QUADRIDGE TECHNOLOGIES  and allocated to Your User Account. QUADRIDGE TECHNOLOGIES  reserves the right to stop accepting debit or credit cards from one or more issuers.
                        <br/><br/>
                        <strong>4.2</strong> Charges and Rates: You shall pay QUADRIDGE TECHNOLOGIES  any applicable charges and rates as stated on the Q.CONFERENCE website / wapsite /application in connection with Your purchase and use of the Software Product/Service. You will be able to pay with Your QUADRIDGE TECHNOLOGIES  Credit balance or with any other payment method available. QUADRIDGE TECHNOLOGIES  reserves the right to revise the rates at any time, at its sole discretion and QUADRIDGE TECHNOLOGIES  will endeavor, within reasonable means, to notify You of, prior to or shortly after, any such changes. If You do not wish to accept such adjustment of rates, You may terminate Your User Account(s). The new rate will apply to Your next purchase. You agree that by continuing to purchase and/or use the Software Product/Service, You accept the new rates. You are not entitled for any change in rate/service once you subscribed/purchased the service.
                        <br/><br/>
                        <strong>4.3</strong> Refund: QUADRIDGE TECHNOLOGIES  recommends you to fully test the software to your satisfaction before making the payment as the software can be tried in a free trial mode. QUADRIDGE TECHNOLOGIES  does not provide any facility to cancel the subscription or entertain claims for any refunds for the same.
                        <br/><br/>
                        <strong>4.4</strong> Delivery Mode: QUADRIDGE TECHNOLOGIES  offers the Service as online service that can be accessed by Internet browser. QUADRIDGE TECHNOLOGIES  does not provide physical delivery of any commodity or service at your home or office address.
                        <br/><br/>

                        <strong>5. TERMINATION</strong><br/>
                        <strong>5.1</strong> The agreement will be effective as of the date of Your first use of any of the Software Product/Service and will remain effective until terminated by either QUADRIDGE TECHNOLOGIES  or its Affiliates or You as set out below.
                        <br/><br/>
                        <strong>5.2</strong> Without limiting other remedies, QUADRIDGE TECHNOLOGIES  or its Affiliates may terminate these Terms of Service with immediate effect, automatically and without recourse to the courts, and may limit, suspend, or terminate Your use of the Software Product/Service, prohibit access to Q.CONFERENCE website/wapsite/application, remove hosted content, and take technical and legal steps to keep You off the Q.CONFERENCE website/wapsite/application if we think that You are in breach of these Terms of Service, creating problems, possible legal liabilities, acting inconsistently with the letter or spirit of our policies, infringing someone else’s intellectual property rights, engaging in fraudulent, immoral or illegal activities, if You purchased QUADRIDGE TECHNOLOGIES  Credit from an unauthorized reseller, or for other similar reasons, with immediate effect and without recourse to the courts. QUADRIDGE TECHNOLOGIES  shall effect such termination by preventing Your access to Your User Account and to the Software Product/Service.
                        <br/><br/>

                        <strong>6. DISCLAIMER OF WARRANTIES; LIMITATION OF LIABILITY</strong><br/>
                        <strong>6.1</strong> Except as warranted in this TOS, QUADRIDGE TECHNOLOGIES  expressly disclaim all warranties of any kind, whether express or implied or statutory, including, but not limited to the implied warranties of merchantability, fitness for a particular purpose, data accuracy, validity, integrity, currentness, adequacy & completeness, and any warranties relating to non-infringement in the Service.
                        <br/><br/>
                        <strong>6.2</strong> QUADRIDGE TECHNOLOGIES  makes no warranty that the Service will meet your requirements.
                        <br/><br/>
                        <strong>6.3</strong> QUADRIDGE TECHNOLOGIES  shall be not responsible or liable for the authenticity, accuracy, completeness, errors, omission, typographic errors, disruption, delay, interruption, failure, deletion, defect of any information/content etc in this Service or web site/wapsite/application or any part thereof.
                        <br/><br/>
                        <strong>6.4</strong> QUADRIDGE TECHNOLOGIES  shall not incur any liability direct or indirect, to you or any third party, as a consequence of non-functioning of Service. QUADRIDGE TECHNOLOGIES  shall not be responsible for any downtime of Service.
                        <br/><br/>
                        <strong>6.5</strong> QUADRIDGE TECHNOLOGIES  shall not incur any liability, loss, claim, expense or costs in relation to any unauthorized use of your credit card number or any misappropriation of payments due to use of any transaction on the Q.CONFERENCE web site/wapsite/application.
                        <br/><br/>
                        <strong>6.6</strong> QUADRIDGE TECHNOLOGIES  does not warrant that the Service will be uninterrupted, timely, secure, or error free; nor does it make any warranty as to the results that may be obtained from the use of the Service or as to the accuracy or reliability of the Service/Content.
                        <br/><br/>
                        <strong>6.7</strong> In no event will the liability of QUADRIDGE TECHNOLOGIES  for any claim, whether in contract, tort, or any other theory of liability, exceed the greater of the amount actually paid by you for subscribing to the Service, if applicable.
                        <br/><br/>

                        <strong>7. PURCHASES AND PREMIUM SERVICES</strong><br/>
                        <strong>7.1</strong> QUADRIDGE TECHNOLOGIES  may from time to time give you the opportunity to purchase or subscribe to Software Product/Service on QUADRIDGE TECHNOLOGIES  (collectively, "Premium Services"). You agree that your purchase or subscription of any Premium Service will be subject to applicable terms and conditions.
                        <br/><br/>
                        <strong>7.2</strong> Any trial promotion (such as free trial time or trial access to a Premium Service) must be used within the specified time of the trial. You must cancel your account before the end of the trial period to avoid being charged a membership fee. Please note, however, that even during any free trial or other promotion, you will still be responsible for any purchases and surcharges incurred using your account and that you remain responsible for any telecommunications charges or Internet access charges that you may incur while you use a Premium Service.
                        <br/><br/>

                        <strong>8. MISCELLANEOUS</strong><br/>
                        <strong>8.1</strong> Software Product/Service, including technical data, is subject to Indian export control laws, and may be subject to export or import regulations in other countries. You agree to comply strictly with all such regulations.
                        <br/><br/>
                        <strong>8.2</strong> Any waiver by QUADRIDGE TECHNOLOGIES  of any default or breach hereunder shall not constitute a waiver of any provision of this TOS or of any subsequent default or breach of the same or a different kind.
                        <br/><br/>
                        <strong>8.3</strong> Any delay on the part of QUADRIDGE TECHNOLOGIES  in exercising any right shall not operate as a waiver of, or impair, any such right. No partial action shall preclude any other or further exercise of any other rights.
                        <br/><br/>
                        <strong>8.4</strong> This TOS is governed by the laws of the Republic of India. You consent to the exclusive jurisdiction and venue of courts in Mumbai, India in all disputes arising out of or relating to the use of this Web site or Service. Use of this Web site is unauthorized in any jurisdiction that does not give effect to all provisions of this TOS, including without limitation, this paragraph.
                        <br/><br/>
                        <strong>8.5</strong> You agree that no joint venture, partnership, employment, or agency relationship exists between You and QUADRIDGE TECHNOLOGIES  as a result of this TOS.
                        <br/><br/>
                        <strong>8.6</strong> If any part of this TOS is determined to be invalid or unenforceable pursuant to applicable law including, but not limited to, the warranty disclaimers and liability limitations set forth above, then the invalid or unenforceable provision will be deemed superseded by a valid, enforceable provision that most closely matches the intent of the original provision and the remainder of the TOS shall continue in effect.
                        <br/><br/>
                        <strong>8.7</strong> This TOS constitutes the entire agreement between You and QUADRIDGE TECHNOLOGIES  with respect to Service and it supersedes all prior or contemporaneous communications and proposals, whether electronic, oral or written between with between You and QUADRIDGE TECHNOLOGIES  respect to Service.
                        <br/><br/>
                        If you have any questions about this TOS, write to us at <a href="mailto:<?php echo $CONST_CONTACT_EID; ?>"><?php echo $CONST_CONTACT_EID; ?> or contact us at <strong>+91 22 6671 3517</strong>.
                    </p>

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