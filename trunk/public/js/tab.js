	var id;
	function $($id){
		id = document.getElementById($id);
		return id;
	}
	
	function selectTab03Syn ( i )
	{
		switch(i){
			case 1:
			$("TabTab03Con1").style.display="block";
			$("TabTab03Con2").style.display="none";
			$("TabTab03Con3").style.display="none";
			$("TabTab03Con4").style.display="none";
			$("font1").style.color="#000000";
			$("font2").style.color="#ffffff";
			$("font3").style.color="#ffffff";
			$("font4").style.color="#ffffff";
			break;
			case 2:
			$("TabTab03Con1").style.display="none";
			$("TabTab03Con2").style.display="block";
			$("TabTab03Con3").style.display="none";
			$("TabTab03Con4").style.display="none";
			$("font1").style.color="#ffffff";
			$("font2").style.color="#000000";
			$("font3").style.color="#ffffff";
			$("font4").style.color="#ffffff";
			break;
			case 3:
			$("TabTab03Con1").style.display="none";
			$("TabTab03Con2").style.display="none";
			$("TabTab03Con3").style.display="block";
			$("TabTab03Con4").style.display="none";
			$("font1").style.color="#ffffff";
			$("font2").style.color="#ffffff";
			$("font3").style.color="#000000";
			$("font4").style.color="#ffffff";
			break;
			case 4:
			$("TabTab03Con1").style.display="none";
			$("TabTab03Con2").style.display="none";
			$("TabTab03Con3").style.display="none";
			$("TabTab03Con4").style.display="block";
			$("font1").style.color="#ffffff";
			$("font2").style.color="#ffffff";
			$("font3").style.color="#ffffff";
			$("font4").style.color="#000000";
			break;
		}
	}