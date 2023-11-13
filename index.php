<?php
session_start();

$auth = $_SESSION['auth'] ?? false;
$username = $_POST['login'] ?? null;
$password = $_POST['password'] ?? null;

$users = ['admin' => 'admin'];

if (null !== $username || null !== $password) {
    if ($password === $users[$username]) {
        $_SESSION['auth'] = true;
    }
}

?>
<html lang="en">
	<head>
    	<meta charset="UTF-8">
    	<title>Конвертер валют</title>
	</head>
	<style>
		body{
			height: 100vh;
			width: 100vw;
			margin: 0;
			display: flex;
		}
		form{
			display: flex;
			flex-direction: column;
  			align-items: center;
		}
		.converter{
			padding: 15px;
			margin: auto;
			border: red solid 1px;
  			background-color: rgb(255, 245, 242);
  			border-radius: 25px;
  			text-align: center;  display: flex;	
  			flex-direction: column;
  			width: 500px;
		}
		input, select{
			display: block;
			width: 200px;
		}
		.convert_item input, .convert_item select{
			float: right;
		}
		.convert_item{
			margin-right: auto;
			margin-left: auto;
			width: 300px;
			margin-bottom: 10px;
		}
	</style>
	<body>
	<?php
	if(!$auth) { ?>
		<div class='converter'>
		  	<form action="" method="post">
		      	<input name="login" type="text" placeholder="Логин">
		      	<input name="password" type="password" placeholder="Пароль">
		      	<input name="submit" type="submit" value="Войти">
		  	</form>
      	</div>
	<?php }else{ ?>
	  	<?php
			$valutes = simplexml_load_file("http://www.cbr.ru/scripts/XML_daily.asp");
	  	?>
		<div class='converter'>
			<div class="convert_item">
				Количество 
			    <input type="number" id="val" value="1" />
			</div>
			<div class="convert_item">
				Из 
			    <select id="cur1">
			    	<option value='1'>Российский рубль</option>
			    	<?php 
			    	foreach ($valutes->Valute as $valute) {
						?><option value='<?=$valute->Value?>'><?=$valute->Name?></option><?
					} ?>
			    </select>
			</div>
			<div class="convert_item">
				В 
			    <select id="cur2">
			    	<option value='1'>Российский рубль</option>
			    	<?php 
			    	foreach ($valutes->Valute as $valute) {
						?><option value='<?=$valute->Value?>'><?=$valute->Name?></option><?
					} ?>
			    </select>
			</div>
			<div class="convert_item">
			    <div>Результат <span id="result">1</span></div>
			</div>
      	</div>
  		<script>
  			window.onload = function () {
			    let val = document.getElementById('val');
			    let valutein = document.getElementById('cur1');
			    let valuteout = document.getElementById('cur2');
			    let result = document.getElementById('result');
			    function convert() {
			        if(valutein.value === valuteout.value){
			            result.innerText = val.value;
			        } else {
			            result.innerHTML = Math.ceil((val.value*parseFloat(valutein.value.replace(",", "."))/parseFloat(valuteout.value.replace(",", ".")))*100)/100;
			        }
			    }
			    val.oninput = function () {
			        convert();
			    };
			    valutein.onchange = function () {
			        convert();
			    };
			    valuteout.onchange = function () {
			        convert();
			    }
			}
  		</script>
	<?php } ?>

	</body>
</html>