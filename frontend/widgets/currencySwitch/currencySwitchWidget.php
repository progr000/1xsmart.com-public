<?php
namespace frontend\widgets\currencySwitch;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class currencySwitchWidget extends Widget
{
    private $currentCurrency;
    private $listCurrencies;
    public $currentUrl;
    public $theme = 'light';

    public function init()
    {
        parent::init();

        $this->currentUrl = Yii::$app->request->getUrl();
        //$this->currentCurrency = Yii::$app->session->get('current----_currency', Yii::$app->params['exchange']['default']);
        $this->currentCurrency = Yii::$app->request->cookies->getValue('_currency', Yii::$app->params['exchange']['default']);
        //var_dump($this->currentCurrency);
        $this->listCurrencies = Yii::$app->params['exchange']['usd'];
    }

    public function run()
    {
        $vars = '';
        foreach ($this->listCurrencies as $k=>$v) {
            if ($this->currentCurrency != $k) {
                $vars .= '<div class="dropdown__item"><a class="dropdown__link" href="' . Url::to(['site/set-currency', 'currency' => $k], CREATE_ABSOLUTE_URL) . '">' . $v['name'] . '</a></div>';
            }
        }

        if ($vars != '') {
            $vars = '<div class="dropdown__dropdown">
                        ' . $vars . '
                     </div>';
        }

        return '<div class="dropdown ' . ($this->theme == 'dark' ? 'dropdown--dark' : '') . ' currency-select">
                    <div class="dropdown__item _current">' . $this->listCurrencies[$this->currentCurrency]['name'] . '</div>
                    '.$vars.'
                </div>'."\n";
    }
}
