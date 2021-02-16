<?php
	function percentageGetter($a, $b)
	{
		$c  = ($a/$b)* 100;
		return ceil($c);
	}
?>

@extends('layouts/main')
@section('content-body')
<div style="display: flex;">   

   <!-- main side bar -->
   <v-msidebar></v-msidebar>   
   <!-- end main side bar -->

   <div style="width: 100%; " class="sysbg">
     <v-systopnave title="My Courses" username='said'></v-systopnave>     
     <div class="row p-3" style="width: 98%; margin: 50px auto;height: 75.5vh;overflow-y: scroll;" class="">
     	<div class="col-lg-11 col-md-12 col-sm-12 col-xs-12">   
	     	<div class="row d-lg-flex" style="">	     		
		     	<div class="col-md-4 pt-1 pl-0 ml-0 mb-5">
		     		<span style="font-family: inherit;font-weight: 500;font-size: 0.9em;" >Enrolled Courses</span>
		     	</div>
				<div class="some-input-field-class col-md-8 d-flex justify-content-between p-0 mb-5" >					
					<span style="display: inline;">						
		     			<input name="filter" id="filter" class="input-search2" placeholder="Search V-labs">
						<span class="fa fa-search serachicon2"></span> 				   				   			
					</span>
					<div style="display: inline-block;">
						<span style="font-family: inherit;font-weight: 300;font-size: 0.9em;">Sort By</span>
						<button class="sort sortbtn" data-sort="name">Name <span class="fa fa-chevron-down"></span></button>
						<span class="ml-2 fa fa-long-arrow-up pl-3" style="border-left: 1px solid #ccc;font-weight: 200;"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<!-- experiment box -->
				<div class="p-4 w-100 bg-white shadow-sm my-3" style="border-radius: 9px;">
					<div class="d-flex justify-content-between align-items-center mb-2">
						<div class="font ">
							<h3 class="fw6">PHY 107</h3>
							<p class="my-1 font2 " style="color:#888;font-size: 0.85em;">But I must explain to you how you will go about this experiment</p>
							<p class="my-0 fw5" style="font-size: 0.9em;">4 Practicals</p>
						</div>
						<div>
							<a href="" class="sysbtn-md p-success text-white">Continue <span class="fa fa-arrow-right text-white"></span></a>
						</div>
					</div>					
					<v-progress evalue=2 avalue=4></v-progress>
				</div>
				<!-- end experiment box -->

				<!-- experiment box -->
				<div class="p-4 w-100 bg-white shadow-sm my-3" style="border-radius: 9px;">
					<div class="d-flex justify-content-between align-items-center mb-2">
						<div class="font ">
							<h3 class="fw6">CHM 107</h3>
							<p class="my-1 font2 " style="color:#888;font-size: 0.85em;">But I must explain to you how you will go about this experiment</p>
							<p class="my-0 fw5" style="font-size: 0.9em;">4 Practicals</p>
						</div>
						<div>
							<a href="" class="sysbtn-md p-success text-white">Continue <span class="fa fa-arrow-right text-white"></span></a>
						</div>
					</div>
					<v-progress evalue=1 avalue=4></v-progress>					
				</div>
				<!-- end experiment box -->

					<!-- experiment box -->
				<div class="p-4 w-100 bg-white shadow-sm my-3" style="border-radius: 9px;">
					<div class="d-flex justify-content-between align-items-center mb-2">
						<div class="font ">
							<h3 class="fw6">BIO 107</h3>
							<p class="my-1 font2 " style="color:#888;font-size: 0.85em;">But I must explain to you how you will go about this experiment</p>
							<p class="my-0 fw5" style="font-size: 0.9em;">4 Practicals</p>
						</div>
						<div>
							<a href="" class="sysbtn-md p-success text-white">Start <span class="fa fa-arrow-right text-white"></span></a>
						</div>
					</div>
					<v-progress evalue=0 avalue=4></v-progress>
					
				</div>
				<!-- end experiment box -->

					<!-- experiment box -->
				<div class="p-4 w-100 bg-white shadow-sm my-3" style="border-radius: 9px;">
					<div class="d-flex justify-content-between align-items-center mb-2">
						<div class="font ">
							<h3 class="fw6">Virtual Optics</h3>
							<p class="my-1 font2 " style="color:#888;font-size: 0.85em;">But I must explain to you how you will go about this experiment</p>
							<p class="my-0 fw5" style="font-size: 0.9em;">4 Practicals</p>
						</div>
						<div>
							<a  class="sysbtn-md p-success text-white" style="cursor:not-allowed;"><span class="fa fa-check-circle text-white"></span> completed </a>
						</div>
					</div>
					<v-progress evalue=4 avalue=4></v-progress>					
				</div>
				<!-- end experiment box -->


	     	</div>  		

     	</div>
     </div>

   </div>
	
</div>
@endsection

<!-- use this it will give you animate play icon  on embeded video-->
