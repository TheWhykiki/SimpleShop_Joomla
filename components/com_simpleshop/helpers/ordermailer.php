<?php


class OrdermailerHelper
{



	function formatCurrency($value) {
		//var_dump($value);die;
		return '€' . number_format($value, 2);
	}


	function mailOrder($cartData, $orderID, $userData)
	{
		setlocale(LC_MONETARY, 'de_DE');
		$app = Jfactory::getApplication();



		$table =    '<table border="1" cellpadding="2" cellspacing="2" style="text-align: left;">';
		$table .=   '	<thead>';
		$table .=   '		<tr>';
		$table .=   '			<th>';
		$table .=   '			    Produktbezeichnung';
		$table .=   '			</th>';
		$table .=   '			<th>';
		$table .=   '			    Menge';
		$table .=   '			</th>';
		$table .=   '			<th>';
		$table .=   '			    Einzelpreis';
		$table .=   '			</th>';
		$table .=   '			<th>';
		$table .=   '			    Gesamtpreis';
		$table .=   '			</th>';
		$table .=   '		</tr>';
		$table .=   '	<thead>';
		$table .=   '	<tbody>';

		$orders = json_decode($cartData);

		$gesamtsumme = array();
		foreach($orders as $order){
			$gesamtsumme[] = $order->produkt_preis * $order->counter;
			$table .= '	<tr>';
			$table .= '		<td>';
			$table .= $order->produkt_titel;
			$table .= '		</td>';
			$table .= '		<td>';
			$table .= $order->counter;
			$table .= '		</td>';
			$table .= '		<td>';
			$table .=  $this->formatCurrency($order->produkt_preis);
			$table .= '		</td>';
			$table .= '		<td>';
			$table .=  $this->formatCurrency($order->produkt_preis * $order->counter);
			$table .= '		</td>';
			$table .= '	</tr>';
		}

		$table .= '	<tr>';
		$table .= '		<td colspan="3">';
		$table .= '		Gesamtsumme der Bestellung:';
		$table .= '		</td>';
		$table .= '		<td>';
		$table .= $this->formatCurrency(array_sum($gesamtsumme));
		$table .= '		</td>';
		$table .= '	<tr>';
		$table .=   '	</tbody>';
		$table .=   '</table>';

		$mailtext = '<html>';
		$mailtext .=    '<head>';
		$mailtext .=        '<title>'.JText::_('COM_SIMPLESHOP_ORDER_ID').$orderID .' ) im Webshop von BDK Online </title>';
		$mailtext .=    '</head>';
		$mailtext .=    '<style>';
		//$mailtext .=        $style;
		$mailtext .=    '</style>';
		$mailtext .=    '<body>';
		$mailtext .=        '<h1>Folgende Bestellung ID-Nr: '.$orderID.'  wurde im Webshop aufgegeben</h1>';
		$mailtext .=        '<p><strong>Kundendaten:</strong></p>';
		$mailtext .=        '<p>Name: '.$userData['name'].'</p>';
		$mailtext .=        '<p>E-Mail: '.$userData['email'].'</p>';
		$mailtext .=        '<p>Adresse: '.$userData['strasse'].', '.$userData['plz'].' '.$userData['ort'].'</p>';
		$mailtext .=       $table;
		$mailtext .=        '</table>';
		$mailtext .= '	</body>';
		$mailtext .= '</html>';

		$empfaenger = "info@whykiki.de";
		$absender   = "BDK Online";
		$subject    = "Bestellung ID-Nr.:  ". $orderID." im BDK Online Webshop" ;

		//$send = JFactory::getMailer()->isHtml(true)->sendMail($absender, 'BDK', $empfaenger, $subject, $mailtext);

		$mailer =  JFactory::getMailer();
		$mailer->setSender($absender);
		$mailer->addRecipient($empfaenger);
		//$mailer->addRecipient('info@whykiki.de');
		$mailer->addRecipient($userData['email']);
		$mailer->isHtml( true);
		$mailer->CharSet  = 'UTF-8';
		$mailer->Encoding = 'base64';
		$mailer->setSubject($subject);
		$mailer->setBody($mailtext);
		$mailer->Send();
		//var_dump('test');die;


		return 'Mail sent';

	}
}