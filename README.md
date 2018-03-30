MERHABA
# TCMB (Central Bank of Turkey) exchange rates can be queried on
#					http://www.tcmb.gov.tr/kurlar/kurlar_tr.html

# The XML service by TCMB
# Past date XML link for a specific date, e.g. 05 March 2018 is
#			 			http://www.tcmb.gov.tr/kurlar/201803/05032018.xml

# Today's link is
#						http://www.tcmb.gov.tr/kurlar/today.xml

# XML Service returns the exchange rate table below:

/*
      "Currency Code"	"Unit"	"Currency"	"Forex Buying"	"Forex Selling"	"Banknote Buying"	"Banknote Selling"
  0		USD/TRY					1				ABD DOLARI	39276	39347	39249	39406
  1		AUD/TRY					1				AVUSTRALYA DOLARI	30130	30326	29991	30508
  2	 	DKK/TRY					1				DANİMARKA KRONU	0.64637	0.64954	0.64591	0.65104
  3	 	EUR/TRY					1				EURO	48223	48309	48189	48382
  4	 	GBP/TRY					1				İNGİLİZ STERLİNİ	55045	55332	55007	55415
  5	 	CHF/TRY					1				İSVİÇRE FRANGI	41075	41339	41013	41401
  6	 	SEK/TRY					1				İSVEÇ KRONU	0.47635	0.48128	0.47602	0.48239
  7	 	CAD/TRY					1				KANADA DOLARI	30097	30233	29986	30348
  8	 	KWD/TRY					1				KUVEYT DİNARI	130175	131879	128223	133857
  9	 	NOK/TRY					1				NORVEÇ KRONU	0.50643	0.50984	0.50608	0.51101
  10	SAR/TRY					1				SUUDİ ARABİSTAN RİYALİ	10473	10492	10395	10571
  11	JPY/TRY					100			JAPON YENİ	36850	37094	36714	37235
  12	BGN/TRY					1				BULGAR LEVASI	24514	24835
  13	RON/TRY					1				RUMEN LEYİ	10274	10409
  14	RUB/TRY					1				RUS RUBLESİ	0.06789	0.06878
  15	IRR/TRY					100			İRAN RİYALİ	0.01036	0.01050
  16	CNY/TRY					1				ÇİN YUANI	0.61694	0.62501
  17	PKR/TRY					1				PAKİSTAN RUPİSİ	0.03392	0.03436
*/

/*
  XML SAMPLE

  $exchangeRates = simplexml_load_file("http://www.tcmb.gov.tr/kurlar/201803/05032018.xml");

  // dolar
  $usd_buy = $exchangeRates->Currency[0]->BanknoteBuying;
  $usd_sell = $exchangeRates->Currency[0]->BanknoteSelling;

  // euro
  $euro_buy = $exchangeRates->Currency[3]->BanknoteBuying;
  $euro_sell = $exchangeRates->Currency[3]->BanknoteSelling;

  // British pound sterling
  $pound_buy = $exchangeRates->Currency[4]->BanknoteBuying;
  $pound_sell = $exchangeRates->Currency[4]->BanknoteSelling;
*/


# getExchangeRate function returns 0 for erroneous date/time queries (e.g. future dates, etc.)
# getExchangeRate function returns the latest available exchange rates for holidays or non-operating days.
# TCMB updates exchange rates every day at exactly 15.30 PM.
#			Querying "today's" rate with time earlier than 15.30 returns previous available exchange rate!
#			Other than "today", querying any other day returns the rate after 15.30 PM which is returned by the service itself.


# PARAMETERS & USAGE ///////////////////////////////////////////////////////
# $currencyType: integer between 0 - 17 to be selected from above table's first column
#							e.g. 0:USD, 3:EURO, 4:GBP, ...

# $dateWithTime: string of the form dd/mm/YYYY - hh:mm:ss
# ( / . :  or other separators does not make any difference)
# 						e.g. "21.03.2018 - 16:15:27"
#							e.g. "21/03/2018 - 16:15:27"
