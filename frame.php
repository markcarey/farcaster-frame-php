<?php
# initial frame:
$frame = [];
$frame['title'] = 'Frame';
$frame['image'] = 'https://frm.lol/images/initial.png';
$frame['post_url'] = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$frame['buttons'] = [
    [
        'label' => 'POST Button',
        'action' => 'post',
    ],
    [
        'label' => 'Link Button',
        'action' => 'link',
        'target' => 'https://farcaster.xyz',
    ],
    [
        'label' => 'Redirect Button',
        'action' => 'post_redirect',
    ]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputJSON = file_get_contents('php://input');
    $body = json_decode($inputJSON, TRUE); //convert JSON into array
    if ($body['untrustedData']['buttonIndex'] === 1) {
        # this is a action=post button press for button #1
        $frame['title'] = 'Frame POST Received';
        $frame['image'] = 'https://frm.lol/images/post.png';
        # we could change the buttons here or remove them
        # $frame['buttons'] = [...];
    } else if ($body['untrustedData']['buttonIndex'] == 2) {
        # this is a action=link button press for button #2
        # more code here if button #2 was a post button
        # since button #2 is a link button, this code will not be executed

    } else if ($body['untrustedData']['buttonIndex'] == 3) {
        # this is a action=post_redirect button press for button #3
        # we could do code here before redirecting
        header('Location: https://warpcast.com');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $frame['title']; ?></title>
        <meta charSet="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="fc:frame" content="vNext" />
        <meta name="fc:frame:image" content="<?php echo $frame['image']; ?>" />
        <meta name="fc:frame:post_url" content="<?php echo $frame['post_url']; ?>" />

        <?php foreach ($frame['buttons'] as $index => $button) { ?>
            <meta name="fc:frame:button:<?php echo $index+1; ?>" content="<?php echo $button['label']; ?>" />
            <meta name="fc:frame:button:<?php echo $index+1; ?>:action" content="<?php echo $button['action']; ?>" />
            <?php if ($button['action'] === 'link') { ?>
                <meta name="fc:frame:button:<?php echo $index+1; ?>:target" content="<?php echo $button['target']; ?>" />
            <?php } ?>
        <?php } ?>
        <meta name="fc:frame:image:aspect_ratio" content="1:1" />
        <meta name="og:image" content="<?php echo $frame['image']; ?>" />
        <meta name="og:title" content="<?php echo $frame['title']; ?>" />
    </head>

    <body>
        <h1><?php echo $frame['title']; ?></h1>
        <div>
            <img src="<?php echo $frame['image']; ?>" />
        </div>
    </body>

</html>