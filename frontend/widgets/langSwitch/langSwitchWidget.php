<?php
namespace frontend\widgets\langSwitch;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class langSwitchWidget extends Widget
{
    private $currentLanguage;
    private $listLanguages;
    public $currentUrl;
    private $tplUrl;
    public $theme = 'light';

    public function init()
    {
        parent::init();

        $this->currentUrl = Yii::$app->request->getUrl();
        $this->currentLanguage = Yii::$app->language;
        $this->listLanguages = Yii::$app->components['urlManager']['languages'];

        //var_dump(Yii::$app->components['urlManager']['languages']); exit;

        /* подготовка списка языков*/
        foreach ($this->listLanguages as $k=>$v) {
            if ($this->currentLanguage == $v) {
                unset($this->listLanguages[$k]);
            }
        }

        /* подготовка шаблона юрла */
        //var_dump($this->currentUrl);
        $regexp = "/^\/" . $this->currentLanguage . "($|\/)/";
        if (preg_match($regexp, $this->currentUrl)) {
            $this->tplUrl = preg_replace($regexp, "/{LANG}/", $this->currentUrl);
        } else {
            $this->tplUrl = "/{LANG}" . $this->currentUrl;
        }
        //var_dump($this->currentUrl);
        //var_dump($this->tplUrl);exit;

    }

    public function run()
    {
        $vars = '';
        foreach ($this->listLanguages as $k=>$v) {
            $vars .= '<div class="dropdown__item"><a href="' . Url::to(str_replace('{LANG}', $v, $this->tplUrl), CREATE_ABSOLUTE_URL) . '"><img src="/assets/xsmart-min/images/flags/' . $v . '.svg"></a></div>';
        }
        if ($vars != '') {
            $vars = '<div class="dropdown__dropdown">
                        ' . $vars . '
                     </div>';
        }
        return '<div class="dropdown ' . ($this->theme == 'dark' ? 'dropdown--dark' : '') . ' dropdown dropdown--icon lng-select">
                    <div class="dropdown__item _current"><img src="/assets/xsmart-min/images/flags/' . $this->currentLanguage . '.svg"></div>
                    '.$vars.'
                </div>'."\n";
    }
}
