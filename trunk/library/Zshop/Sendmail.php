<?php
class Zshop_Sendmail extends Zend_Controller_Plugin_Abstract{

		function sendmail($address,$com,$title,$body){
			require APPLICATION_PATH.("./PHPMailer/class.phpmailer.php");

			$mail = new PHPMailer();   //实例化邮件发送类
			$mail->CharSet = "UTF-8";   //设置编码
			$addr = $address;   // 填写收件人电子邮件

			$mail->IsSMTP(); // 设定使用SMTP服务
			$mail->Host       = $com;      // SMTP 服务器(企业邮件域名)
			$mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
			$mail->Username   = "Ying_2_su@126.com";  // SMTP服务器用户名
			$mail->Password   = "aizou786921";            // SMTP服务器密码
			$mail->Port       = 25;   //端口号
			$mail->From       = "aizou@qq.com";   //邮件发送者email地址
			$mail->FromName   = "xiao"; 
			$mail->AddAddress($addr, "a");
			$mail->Subject    = $title;
			$mail->Body    = $body;
			if(!$mail->Send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
				exit;
			} else {
				echo "Message sent!恭喜，邮件发送成功！";
			}
	}
}
?>