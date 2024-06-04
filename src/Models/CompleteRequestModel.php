<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Constants\Currency;
use Omnipay\Paytr\Constants\PaymentType;
use Omnipay\Paytr\Helpers\Helper;

/**
 * 1. ADIM’da ödeme formunu kullanarak müşteriniz ödeme yaptığında,
 * PayTR sistemi ödeme sonucunu yazılımınıza bildirmelidir ve
 * yazılımınızdan bildirimin alındığına dair cevap almalıdır.
 *
 * Aksi halde, ödeme işlemi tamamlanmaz ve tarafınıza ödeme aktarılmaz.
 *
 * PayTR sistemince ödeme sonuç bildiriminin yapılacağı sayfa (Bildirim URL) tarafınızca belirlenmeli ve
 * Mağaza Paneli’ndeki AYARLAR sayfasında tanımlanmalıdır.
 *
 * Tanımlayacağınız Bildirim URL’ye POST metodu ile ödemenin sonucu (başarılı veya başarısız)
 * her işlem için ayrı olarak gönderilir.
 *
 * Bu bildirime istinaden Bildirim URL’nizde yapacağınız kodlama ile yazılımınızda siparişi onaylamalı veya
 * iptal etmelisiniz, ekrana OK basarak PayTR sistemine cevap vermelisiniz.
 */
class CompleteRequestModel extends BaseModel
{
	public function __construct(?array $abstract)
	{
		parent::__construct($abstract);
	}

	/**
	 * @required
	 * @var string
	 */
	public $merchant_id;

	/**
	 * Mağaza sipariş no: Satış işlemi için belirlediğiniz ve 1. ADIM’da gönderdiğiniz
	 * sipariş numarası
	 *
	 * @required
	 * @var string
	 */
	public $merchant_oid;

	/**
	 * Ödeme işleminin sonucu (success veya failed)
	 *
	 * @required
	 * @var string
	 */
	public $status;

	/**
	 * İşlem başarılı ise ödeme tutarı, işlem başarısız ise sıfır (0) döner.
	 *
	 * @required
	 * @var float
	 */
	public $total_amount;

	/**
	 * Sipariş tutarı: 1. ADIM’da gönderdiğiniz “payment_amount” değeridir.
	 * (100 ile çarpılmış hali gönderilir. 34.56 => 3456)
	 *
	 * @required
	 * @var float
	 */
	public $payment_amount;

	/**
	 * PayTR sisteminden gönderilen değerlerin doğruluğunu kontrol etmeniz için güvenlik amaçlı
	 * oluşturulan hash değeri (Hesaplama ile ilgili olarak örnek kodlara bakmalısınız)
	 *
	 * @required
	 * @var string
	 */
	public $hash;

	/**
	 * Mağazanız test modunda iken veya canlı modda yapılan test işlemlerde 1 olarak gönderilir.
	 *
	 * @required
	 * @var boolean
	 */
	public $test_mode;

	/**
	 * Ödeme şekli: Müşterinin hangi ödeme şekli ile ödemesini tamamladığını belirtir.
	 * 'card' veya 'eft' değerlerini alır.
	 *
	 * @see PaymentType
	 * @var boolean
	 */
	public $payment_type;

	/**
	 * Para birimi: Ödemenin hangi para birimi üzerinden yapıldığını belirtir.
	 * ‘TL’, ‘USD’, ‘EUR’, ‘GBP, ‘RUB’ değerlerinden birini alır.
	 *
	 * @see Currency
	 * @var string
	 */
	public $currency;

	/**
	 * @var integer
	 */
	public $installment_count;

	/**
	 * If request made with store card intent.
	 * This token is the userReference generated by Paytr.
	 *
	 * @var string
	 */
	public $utoken;

	/**
	 * Ödemenin onaylanmaması durumunda gönderilir
	 * (Bkz: 2. Adım İçin Hata Kodları ve Açıklamaları Tablosu)
	 *
	 * @var integer
	 */
	public $fail_code;

	/**
	 * Ödemenin neden onaylanmadığı mesajını içerir
	 * (Bkz: 2. Adım İçin Hata Kodları ve Açıklamaları Tablosu)
	 *
	 * @var string
	 */
	public $fail_reason;

	/**
	 * @required
	 * @var string
	 */
	public $confirmation_hash;

	public function generateToken($salt, $key, $id)
	{
		$hash_string = $this->merchant_oid . $salt . $this->status . $this->total_amount;

		$this->confirmation_hash = Helper::hash($key, $hash_string);
	}
}
