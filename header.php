<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pharmacy Management</title>
    <link rel="stylesheet" href="css/semantic.css" />
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="css/app.css" />
</head>
<body>

<div class="ui page grid">
    <div class="computer tablet only row">
        <div class="ui fixed navbar menu">
            <a href="/pharmacy/?page=home" class="brand item">Pharmacy Management Tool</a>
            <?php create_menu() ?>
            <div class="right menu item">
                <button class="ui primary button login_button">Login</button>
            </div>
        </div>
    </div>
    <div class="mobile only row">
        <div class="ui fixed navbar menu">
            <a href="/pharmacy/?page=home" class="brand item">Pharmacy Management Tool</a>
            <div class="right menu open">
                <a href="" class="menu item">
                    <i class="sidebar icon"></i>
                </a>
            </div>
        </div>
        <div class="ui vertical navbar menu">
            <?php create_menu() ?>
            <div class="item">
                <button class="ui primary button login_button">Login</button>
            </div>
        </div>
    </div>
    <div class="ui modal small login_modal">
        <i class="close icon"></i>
        <div class="header">
            Login
        </div>
        <div class="ui container">
            <div class="ui segment">
                <form class="ui small form">
                    <div class="field">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="somebody@example.com">
                    </div>
                    <div class="field">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Password">
                    </div>
                    <button class="ui primary button" type="submit">Login</button>
                    <div class="ui error message"></div>
                </form>
            </div>
        </div>
    </div>
    <div class="actions">
    </div>
</div>

