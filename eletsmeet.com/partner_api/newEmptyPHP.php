ALTER TABLE `db_eletesmeet_com`.`client_details`     ADD COLUMN `client_secret_key` VARCHAR(50) NULL COMMENT 'Client Secret Key for API Authentication' AFTER `client_creation_dtm`;

SELECT CONVERT_TZ('2015-06-24 15:59:55','+05:30','+00:00');

SELECT MD5(CONCAT('cl00007','letsmeet@axsys.co.uk','ed2b627fa5b00cbc2864c4a0e31e7c34'));

SELECT NOW(),UNIX_TIMESTAMP(UTC_TIMESTAMP()), UNIX_TIMESTAMP();

SELECT MD5(CONCAT('letsmeet@axsys.co.uk','1436765398','L3tSm3e7Ax5ys'));


data=<?xml version="1.0" encoding="iso-8859-1"?>
<scheduleMeeting>
<clientID>cl00007</clientID>
<userEmail>letsmeet@axsys.co.uk</userEmail>
<userPW>7270bc42ee81ce7cf554ac3463f0e5c0</userPW>
<subscriptionID>4</subscriptionID>
<meetingTitle>Test Meeting - Plan S</meetingTitle>
<scheduleType>N</scheduleType>
<scheduleDateTime>2015-07-13 16:29:58</scheduleDateTime>
<timezone>Europe/London</timezone>
<inviteeList>
<invitee>
<inviteeEmail>mitesh.shah@quadridge.com</inviteeEmail>
<inviteeNickName>mitesh</inviteeNickName>
<inviteeIDDCode>91</inviteeIDDCode>
<inviteeMobile>9920540000</inviteeMobile>
<moderatorFlag>N</moderatorFlag>
</invitee>
<invitee>
<inviteeEmail>sushrit.shrivastava@quadridge.com</inviteeEmail>
<inviteeNickName>sushrit</inviteeNickName>
<inviteeIDDCode>91</inviteeIDDCode>
<inviteeMobile>9167997663</inviteeMobile>
<moderatorFlag>Y</moderatorFlag>
</invitee>
</inviteeList>
<timestamp>1436765398</timestamp>
<passCode>447533d5bd11bdf6fc7d7aca14ebebca</passCode>
</scheduleMeeting>&protocolID=1

http://172.16.1.128/eletsmeet.com/partner_api/getJoinMeetingURL.php?clientID=cl00007&userEmail=letsmeet@axsys.co.uk&userPW=7270bc42ee81ce7cf554ac3463f0e5c0&scheduleID=55a39fa932738&TS=1436765398&passCode=447533d5bd11bdf6fc7d7aca14ebebca&protocolID=1


http://172.16.1.128/eletsmeet.com/partner_api/getScheduleMeetings.php?clientID=cl00001&userEmail=mitesh.shah@quadridge.com&userPW=96ecc53c4a15e4e13707d70656dc7c14&meetingType=F&TS=1436583949&passCode=992c1362bb41c917512f3793e02a004a&protocolID=1


http://172.16.1.128/eletsmeet.com/partner_api/getJoinMeetingURL.php?clientID=cl00001&userEmail=mitesh.shah@quadridge.com&userPW=96ecc53c4a15e4e13707d70656dc7c14&scheduleID=55a0d603c8766&TS=1436583949&passCode=27885ee4c1b045e9d1dea5cc20e9157e&protocolID=1