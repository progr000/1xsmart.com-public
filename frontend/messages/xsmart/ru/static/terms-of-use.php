<?php
use yii\helpers\Url;

return [
    'title' => "Условия эксплуатации",

    'text' => '
        <h1 class="page-title text-center">Условия эксплуатации</h1>
        <div class="">

            <h1>Условия использования веб-сайта</h1>

            <h2>1. Условия</h2>

            <p>Заходя на этот веб-сайт, доступный с ' . Yii::getAlias('@frontendDomain') . ', Вы соглашаетесь соблюдать настоящие Условия использования веб-сайта и соглашаетесь с тем, что Вы несете ответственность за соглашение с любыми применимыми местными законами. Если Вы не согласны с любым из этих условий, Вам запрещается доступ к этому сайту. Материалы, содержащиеся на этом веб-сайте, защищены законом об авторском праве и товарных знаках.</p>

            <h2>2. Лицензия на использование</h2>

            <p>Разрешается временно загрузить одну копию материалов с веб-сайта {APP_NAME} только для личного некоммерческого временного просмотра. Это предоставление лицензии, а не передача права собственности, и в соответствии с этой лицензией Вы не можете:</p>

            <ul>
                <li>изменять или копировать материалы;</li>
                <li>использовать материалы в коммерческих целях или для публичного показа;</li>
                <li>пытаться реконструировать любое программное обеспечение, содержащееся на веб-сайте {APP_NAME};</li>
                <li>удалить из материалов любые авторские права или другие записи о правах собственности.;</li>
                <li>передача материалов другому лицу или "зеркальных" материалов на любом другом сервере.</li>
            </ul>

            <p>Это позволит приложению {APP_NAME} прекратить работу в случае нарушения любого из этих ограничений. После прекращения действия ваше право на просмотр также будет прекращено, и вы должны уничтожить все загруженные материалы, находящиеся в вашем распоряжении, будь то печатный или электронный формат. <! - Эти Условия использования были созданы с помощью <a href = "https://www.termsofservicegenerator.net"> Генератора Условий использования </a>.--></p>

            <h2>3. Отказ от ответственности</h2>

            <p>Все материалы на веб-сайте {APP_NAME} предоставляются "как есть". {APP_NAME} не дает никаких гарантий, явных или подразумеваемых, поэтому аннулирует все другие гарантии. Кроме того, {APP_NAME} не делает никаких заявлений относительно точности или надежности использования материалов на своем веб-сайте или иным образом связанных с такими материалами или любыми сайтами, связанными с этим веб-сайтом.</p>

            <h2>4. Ограничения</h2>

            <p>{APP_NAME} или его поставщики не будут нести ответственности за любой ущерб, который возникнет в результате использования или невозможности использования материалов на веб-сайте {APP_NAME}, даже если {APP_NAME} или уполномоченный представитель этого веб-сайта были уведомлены в устной или письменной форме о возможность такого повреждения. В некоторых юрисдикциях не допускаются ограничения подразумеваемых гарантий или ограничения ответственности за случайный ущерб, эти ограничения могут не относиться к Вам.</p>

            <h2>5. Изменения и исправления</h2>

            <p> Материалы, представленные на веб-сайте {APP_NAME}, могут содержать технические, типографские или фотографические ошибки. {APP_NAME} не обещает, что какие-либо материалы на этом веб-сайте являются точными, полными или актуальными. {APP_NAME} может изменять материалы, содержащиеся на своем веб-сайте, в любое время без предварительного уведомления. {APP_NAME} не берет на себя обязательств по обновлению материалов.</p>

            <h2>6. Ссылки</h2>

            <p>{APP_NAME} не проверяет все сайты, связанные с его Веб-сайтом, и не несет ответственности за содержание любого такого связанного сайта. Наличие любой ссылки не означает одобрения сайта {APP_NAME}. Пользователь использует любой связанный веб-сайт на свой страх и риск.</p>

            <h2>7. Изменения в Условиях использования Сайта</h2>

            <p>{APP_NAME} может пересмотреть настоящие Условия использования своего Веб-сайта в любое время без предварительного уведомления. Используя этот веб-сайт, Вы соглашаетесь соблюдать текущую версию настоящих Условий использования.</p>

            <h2>8. Ваша конфиденциальность</h2>

            <p>Пожалуйста, прочтите наш <a href="' . Url::to(['/privacy-policy', 'empty-layout' => Yii::$app->request->get('empty-layout', 0)], true) . '">Политика конфиденциальности.</a></p>

            <h2>9. Применимое Право</h2>

            <p>Любая претензия, связанная с веб-сайтом {APP_NAME}, регулируется законодательством Российской Федерации без учета положений коллизионного права.</p>

        </div>
    '
];
