<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Meme Generator - Home</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <style>
            .meme {
                width: 300px; 
                border: 1px solid silver;
                padding: 0.5em;
                text-align: center;
                margin: 0.5em;
                vertical-align: middle;
            }



        </style>
    </head>
    <body>
        <?php
        session_start();
        require_once './autoload.php';
        $db = new DBSpring();
        $util = new Util();
        
        //Initilize all vairables
        $email = filter_input(INPUT_POST, 'email');
        $pass = filter_input(INPUT_POST, 'pass');
        
        if ( $db->isPostRequest() ) {
            
            $userInfo = $db->getLoginInfo($email);
            $loginHash = $userInfo[0]['password'];
            $userID = $userInfo[0]['user_id'];
            
            if(password_verify($pass, $loginHash)){
                $_SESSION['user_id'] = $userID;
                $_SESSION['loggedin'] = true;
                $util->redirect("loggedIn.php");
            }
        }
        
        $titles = $db->getAllTitles();
        ?>
        <div class="container">
            <br/>
            <a href="./views/signup.php">Sign Up</a>
            <br/>
            <h1>Login</h1>
            <form action="#" method="post">   
                Email: <input name="email" value="<?php echo $email; ?>" /> <br />
                Password: <input type="password" name="pass" value="<?php echo $pass; ?>" /> <br />
                <input type="submit" value="Login" class="btn btn-primary" />
            </form>
            <?php
                $files = array();
                $directory = '.' . DIRECTORY_SEPARATOR . 'uploads';
                $dir = new DirectoryIterator($directory);
                foreach ($dir as $fileInfo) {
                    if ($fileInfo->isFile()) {
                        $files[$fileInfo->getFilename()] = $fileInfo->getPathname();
                    }
                }

                krsort($files);

                foreach ($files as $key => $path):
                    ?> 
                    <div class="meme"> 
                        <a href="views/viewMeme.php?fileName=<?php echo $key ?>"><img src="<?php echo $path; ?>" /></a> <br />
                        <?php 
                        $arraySize = sizeof($titles);
                    for($i=0; $i < $arraySize; $i++) {
                        if($titles[$i]['filename'] == $key){
                            echo $titles[$i]['title'];
                        }
                    }
                        
                        //echo $key; ?>
                        <!-- Place this tag where you want the share button to render. -->
                        <div class="g-plus" data-action="share" data-href="<?php echo $path; ?>"></div> 
                    </div>

                <?php endforeach; ?>


        </div>
        <!-- Place this tag in your head or just before your close body tag. -->
        <script src="https://apis.google.com/js/platform.js" async defer></script>
    </body>
</html>