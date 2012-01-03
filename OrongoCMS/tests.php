<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require 'globals.php';
        require 'TestStorable.php';
        $testStorable = new TestStorable(array("privateString" => 'Hey, this is a string var!'));
        Storage::store('storable', $testStorable, true);
        for($i = 0; $i < 3; $i ++){
            $a = Article::createArticle('Test Article ' . $i, new User(1));
            $a->setContent(trim('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris tellus tellus, mollis et sagittis sed, cursus non ante. Pellentesque laoreet tristique eros id scelerisque. Sed vel odio ac lectus sodales gravida. Quisque augue nulla, ullamcorper pharetra ullamcorper quis, luctus non ipsum. In risus sapien, blandit eu faucibus quis, gravida non leo. Mauris dictum lacinia nulla sit amet gravida. Phasellus pretium nisi ac libero rutrum tempor. Nullam ultrices est nec nibh mollis hendrerit. Maecenas ut eros ut ante accumsan viverra. Aliquam nec fringilla nibh. Vivamus sollicitudin fermentum orci eget tempus. Phasellus mauris mi, egestas non cursus ut, volutpat sed sapien. Aliquam erat volutpat. Phasellus porttitor, eros a semper faucibus, erat est faucibus nulla, id ornare felis felis aliquet odio. Phasellus diam dui, volutpat eget consequat vitae, varius in nunc.

Integer et nibh ligula, nec molestie diam. Maecenas fermentum urna a justo vestibulum sed suscipit arcu tempor. In rutrum, libero id lobortis adipiscing, tellus nisi congue eros, vitae egestas lorem purus quis mi. Suspendisse in turpis eu sapien convallis egestas at eu lorem. Sed id nulla enim. Nunc purus nisi, egestas eget varius sit amet, ullamcorper molestie eros. Nunc nec mauris at sem tempor tempor interdum sed sem. Pellentesque elit sem, fermentum eu aliquet a, varius fermentum sem. Sed sollicitudin urna ut quam convallis eleifend. Sed facilisis, nibh et scelerisque consequat, eros mauris ullamcorper est, a interdum massa nisl in massa. Vestibulum lobortis sem ut mi elementum ac congue mauris faucibus. Cras ut quam sit amet enim viverra fringilla. Duis pulvinar faucibus arcu ut bibendum. Fusce viverra commodo nunc sed dapibus. Nunc ut aliquam felis. Curabitur molestie vestibulum magna eu mattis.

Sed ullamcorper ante elementum leo pellentesque venenatis. Nam ac ipsum id eros pulvinar bibendum at et erat. Proin pellentesque volutpat ligula ac suscipit. Sed euismod, quam eget placerat eleifend, magna eros mattis elit, et pulvinar dolor nisl eget tellus. Nullam sodales laoreet turpis, id vestibulum enim ornare eget. Nam sollicitudin, sem sed varius porttitor, nisl elit facilisis nunc, vitae vestibulum odio libero a tortor. Etiam ac consectetur lorem. Quisque ornare malesuada justo, sit amet interdum leo laoreet sit amet. Morbi sit amet odio massa, et lobortis purus. Nunc vitae magna tellus, a elementum orci. Donec blandit consequat tortor, vitae laoreet nisi dapibus et. Sed scelerisque, nisi at vestibulum tempus, ligula lectus scelerisque lorem, vitae venenatis nibh nisi ac nulla. Proin vel nunc in nunc euismod pretium ut quis est. Sed cursus adipiscing arcu quis porttitor.

Phasellus in vulputate metus. Donec luctus fermentum ipsum eu dictum. Suspendisse potenti. Etiam hendrerit placerat bibendum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nullam vehicula, leo et dignissim ullamcorper, lorem metus aliquet odio, ut vestibulum justo massa vel ante. Mauris eu turpis risus. Proin vitae ante in ante pharetra porttitor. Maecenas eu leo in odio posuere tristique sit amet sit amet lacus. Vestibulum gravida condimentum mauris, sed gravida nunc mollis vel. Morbi sapien massa, pharetra et sagittis eget, pellentesque non nibh. Praesent ut leo rutrum sapien lacinia aliquam non eget ante. In sed mauris eget metus cursus pharetra sed ac mi. Nunc placerat nulla et lorem vehicula convallis. Cras sem diam, commodo vel dignissim sed, auctor nec neque. In quis ante quis felis tincidunt tristique non non augue.

Phasellus dapibus nunc eu magna tempor eu euismod libero molestie. Nullam lacinia nisi non nisi rhoncus ut viverra arcu facilisis. Morbi vitae mollis justo. Pellentesque ligula purus, sodales in dignissim vel, ultrices sed est. Aenean sed enim lorem, eu euismod erat. Ut ac felis a elit placerat tincidunt quis non quam. Donec aliquet lacus eget purus auctor et pellentesque justo laoreet. Praesent odio justo, malesuada non malesuada ac, varius eu leo. Pellentesque elementum tortor ac neque auctor et interdum tortor malesuada. Nam id erat et massa faucibus egestas. Quisque imperdiet consequat lobortis. Aliquam mollis lobortis velit nec auctor. '));
            
        }

        ?>
    </body>
</html>
