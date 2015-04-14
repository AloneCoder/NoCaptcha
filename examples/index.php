<?php

require_once __DIR__ . '/../src/autoload.php';
$siteKey = '';
$secret = '';

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>NoCaptcha Example</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
    <script type="text/javascript">
        $(function () {
            var $nr = $('#nocaptcha_event_result'),
                $sr = $('#nocaptcha_script_result');
            $('#nocaptcha').on('nocaptcha', function (ev) {
                var is_verified = ev.originalEvent.detail.is_verified;
                $nr.text((is_verified) ? 'verified' : 'not verified');
                $sr.text(true);
            });
        });
    </script>

    <script async
            src="https://<?= \NoCaptcha\RequestMethod\SocketGet::NOCAPTCHA_HOST; ?>/captcha?<?= http_build_query(array(
                'public_key' => $siteKey,
            )) ?>" type="text/javascript"></script>
</head>
<body>
<h1>NoCaptcha Example</h1>
<?php if ($siteKey === '' || $secret === ''): ?>
    <h2>Add your keys</h2>
    <p>If you do not have keys already then visit <tt>
            <a href="https://nocaptcha.mail.ru">
                https://nocaptcha.mail.ru</a></tt> to generate them.
        Edit this file and set the respective keys in <tt>$siteKey</tt> and
        <tt>$secret</tt>. Reload the page after this.</p>
<?php elseif (isset($_GET['captcha_value'], $_GET['captcha_id'])): ?>
    <h2><tt>GET</tt> data</h2>
    <tt>
        <pre><?php var_export($_GET); ?></pre>
    </tt>
<?php
    $NoCaptcha = new \NoCaptcha\NoCaptcha($secret, $_GET['captcha_id'], $_GET['captcha_value']);
//    $NoCaptcha = new \NoCaptcha\NoCaptcha($secret, $_GET['captcha_id'], $_GET['captcha_value'], new \NoCaptcha\RequestMethod\SocketGet());

    $resp = $NoCaptcha->verify();
    if ($resp->isSuccess()):
        ?>
        <h2>Success!</h2>
        <p>That's it. Everything is working. Go integrate this into your real project.</p>
        <p><a href="/">Try again</a></p>
    <?php
    else:
        ?>
        <h2>Something went wrong</h2>
        <p>The following error was returned: <ul><?php
        $error = $resp->getError();
        if (isset($error['code'])) {
            echo '<li>Code : ' . $error['code'] . '</li>';
        }
        echo '<li>Message : ' . $error['desc'] . '</li>';
        ?></ul></p>
        <p><a href="/">Try again</a></p>
    <?php
    endif;
else:
    ?>
    <p>Complete the NoCaptcha then submit the form.</p>
    <form>
        <fieldset>
            <legend>An example form</legend>
            <p>Example 1: <input type="text" name="foo" value="foo"></p>

            <p>Example 2: <input type="text" name="bar" value="bar"></p>

            <p>Client script: <span id="nocaptcha_script_result">false</span></p>

            <p>NoCaptcha event: <span id="nocaptcha_event_result">-</span></p>

            <div id="nocaptcha"></div>
            <p><input type="submit" value="Submit"/></p>
        </fieldset>
    </form>
<?php endif; ?>

</body>
</html>
