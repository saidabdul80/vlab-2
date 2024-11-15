<template>	
		<div class="w-100 mt-2 py-3 mx-auto position-relative row" data-title="Welcome!" :data-intro="'Hello '+ currentUser.salute +' '+currentUser.first_name +'👋'" data-step="1">						
			<div class="col-md-2 offset-md-10 pt-3">
				<button v-if="!usersloaderState"   href="#" @click="viewMenu" style="z-index:5;border:0px;" :class="'Menux d-flex justify-content-between p-success text-left position-relative'+ btnclasses+' btn-width-179'">Action <span class="text-white fa fa-chevron-up"></span></button>
				<div class="w-179 " id="Menu" style="position: absolute;z-index:-1;opacity:0; top:40px;">
					<button v-if="!usersloaderState"  href="#" @click="createuser" :class="'p-success  w-100 r-0 '+ btnclasses">Create user <span class="text-white fa fa-chevron-down"></span></button>			
					<button v-if="!usersloaderState" href="#" @click="uploadstudent" :class="'p-success  w-100 r-0 '+ btnclasses">Upload Student <span class="text-white fa fa-cloud-upload"></span></button>			
					<a href="templateFiles/userupload_template.csv" target="_self" :class="'bg-dark  w-100 r-0 '+ btnclasses">Download Template<span class="text-white fa fa-cloud-download"></span></a>			
					<button v-if="usertype" :class="'btn-danger  w-100 r-t-0 '+ btnclasses " @click="swichUser(true)" >See Inactive Users <span class="fa fa-user-times text-white"></span></button>
					<button v-else :class="'bg-success w-100 r-t-0 ' + btnclasses " @click="swichUser(false)" >See Active Users <span class="fa fa-user  text-white"></span></button>			
				</div>
			</div>
      <!-- 	<div class="w-100 col-md-12 mb-2" id="#editArea" style="pointer-events: none;"> 			  
		    <div class="py-2 mx-auto"  style="pointer-events: auto;">		        
				<div class="row w-100 mx-auto px-0">
				<div class="col-sm-6 col-md-3 px-1">
						<select id="sessionid" class="form-control mb-1 mt-1"  placeholder="select session">
							<option value="">By Session</option>
							<option v-for="(session,index) in sessions" :key="index+'_ac'" :value="session.id">{{session.session}}</option>
						</select>	        
					</div>
					<div class="col-sm-6 col-md-3 px-1">
						<select id="roleid" class="form-control mb-1 mt-1" >
							<option value="">By Role</option>		        	
							<option v-for="(role,index) in JSON.parse(roles)" :key="index+'_ab'" :value="role">{{index}}</option>
						</select>
					</div>
					<div class="col-sm-6 col-md-3 px-1">
						<select id="departmentid" class="form-control mb-1 mt-1"  placeholder="department" >
							<option value="">Department</option>		        	
							<option v-for="(department,index) in departments" :key="index+'_ad'" :value="department.id">{{department.code}}</option>
						</select>
					</div> 
					<div class="col-sm-6 col-md-1 pt-1 px-1">
		        		<button class="button mb-1 mt-2" @click="fetchUser" >Go</button>
					</div>
			
					
				</div>
				<hr style="border-top:0.5px solid #ccc;">
		    </div>

		</div> -->		
		<div v-show="loaderState" class="col-md-12">
			<br><br>
          	<v-loader count="2"></v-loader>
         </div>
		 <div v-show="!loaderState"   class=" position-relative row mx-auto mt-5 w-100">				 
			<div v-if="!usersloaderState2" class="col-md-12 notification-table-main forUser v-scroll-x scroll-hidden-y" style="display:none" id="forInActive" >			     			     
				<table id="usertable2" class="table table-hover w-100">
					<thead>
							<tr id="cheadV">							
								<th width="30%">Full Name</th>							
								<th width="40%">Email/Matric Number</th>	            
								<th width="10%">role </th>	            
								<th width="10%">Faculty</th>				            			           
								<th width="10%">Department</th>				            			           
								<th width="10%">Courses</th>				            			           
								<th width="10%">Action</th>
							</tr>
					</thead>
					<tbody>
						<tr v-for="(user, index) in inactiveUser.data" :key="index">	         
							<td width="30%">{{user.name}}</td>
							<td width="40%" style="white-space: nowrap;">
								<code v-if="user.email !=''">{{user.email}}</code>
								<span v-if="user.email != '' && user.matric_number !=''">-</span>
								<span v-if="user.matric_number !=''">{{user.matric_number}}</span>
							</td>	           
							<td width="20%">{{ user.role }}</td>	           
							<td width="20%">{{user.faculty}}</td>	           			                  
							<td width="20%">{{user.department}}</td>	           			                  
							<td width="20%">{{user.courses}}</td>	           			                  
							<td width="15%" >
								<span class="ml-2 fa fa-edit pl-3  fs01 cursor-1" onclick="edituser(user)" style="border-left: 1px solid #ccc;"></span>
								<span class="ml-2 fa fa-trash pl-3  fs01 cursor-1" onclick="deleteuser(user.id)"></span>
							</td>
						</tr>
					</tbody>
				</table>            
			</div>
			<div v-if="!usersloaderState" class="col-md-12 notification-table-main forUser v-scroll-x scroll-hidden-y px-0 " id="forActive" >			     			     
					<table  id="usertable" class="table table-hover w-100">
						<thead>
							<tr id="cheadV">							
								<th width="30%">Full Name</th>							
								<th width="40%">Email/Matric Number</th>	            
								<th width="10%">role </th>	            
								<th width="10%">Faculty</th>				            			           
								<th width="10%">Department</th>				            			           
								<th width="10%">Courses</th>				            			           
								<th width="10%">Action</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(user, index) in users.data" :key="index">	         
								<td width="30%">{{user.name}}</td>
								<td width="40%" style="white-space: nowrap;">
									<code v-if="user.email !=''">{{user.email}}</code>
									<span v-if="user.email != '' && user.matric_number !=''">-</span>
									<span v-if="user.matric_number !=''">{{user.matric_number}}</span>
								</td>	           
								<td width="20%">{{ user.role }}</td>	           
								<td width="20%">{{user.faculty}}</td>	           			                  
								<td width="20%">{{user.department}}</td>	           			                  
								<td width="20%">{{user.courses}}</td>	           			                  
								<td width="15%" >
									<span class="ml-2 fa fa-edit pl-3  fs01 cursor-1" @click="edituser(user)" style="border-left: 1px solid #ccc;"></span>
									<span class="ml-2 fa fa-trash pl-3  fs01 cursor-1" @click="deleteuser(user.id)"></span>
								</td>
							</tr>
						</tbody>
					</table>            
			</div>
			<br><br>
		  </div>
        </div>
</template>

<script>
	import loader from './skeletalLoaderA.vue'; 
	export default{
		data(){
			return{
				vclass1: ' col-md-2 offset-md-4 ',
				vclass2: ' col-md-2',
				btnclasses: "btn mx-auto d-block shadow text-white fs1 font1 btn-lg my-0",				
				users:[],
				inactiveUser:[],
				tableLoaded:false,
				facultiesHTML: null,
				faculties: null,
				departments:null,
				watchfacultyHtml:{value:null},
				uploadlist:[],
				listTrHtml:"",
				sessions:[],
				loaderState:true,
				usersloaderState:true,
				usersloaderState2:true,
				response:'',
				dTable:'',
				table:null,
				table2:null,
				usertype:true,
				setOut:false,
				menuToggle: false
			}
		},
		components:{		
			'v-loader':loader,
		},
		methods: {
			viewMenu:function(){
				if(this.menuToggle){										
					this.hideMenu()
				}else{			
					this.menuToggle = true
					$('#Menu').animate({
						direction: "down",
						opacity:1,
						top:'53px',						
					}, 300, function(){
						});			
					$('#Menu').css('z-index',4);		
				}				
			},
			hideMenu:function(){
				$('#Menu').animate({
						direction: "down",
						opacity:0,
						top:'20px',					
				}, 300, function(){
					$('#Menu').css('z-index',-1);
				});			
				this.menuToggle = false
			},
			activateUser:function(id){
				this.axiosDelete('api/users/activate_user',{'user_id': id},"Activate this user")
			},
		    swichUser(usertype){			
				if(usertype){
					this.usertype = false;
					$('#forActive').hide("slide", {direction: "right"}, 500);					
					$('#forInActive').show("slide", {direction: "left"}, 500);
				}else{
					this.usertype = true;
					$('#forActive').show("slide", {direction: "left"}, 500);
					$('#forInActive').hide("slide", {direction: "right"}, 500);
				}
			},
			createuser:function(){
				this.VueSweetAlert2('v-userform', {
					type:'user',
					faculties: this.faculties,
					departments: this.departments,
					roles:this.roles
				});
			},
			getRoleName:function(role_id){
				var role = JSON.parse(this.roles);
				for(var i in role){						
					if (role[i]==role_id) {
						return i;
						break;
					}
				}				
			},
			fetchUser:function(){
				var session_id = $('#sessionid').val();
				var department_id = $('#departmentid').val();
				var role_id = $('#roleid').val();
				$('#sessionid').css({'border':'1px solid #ccc'});
				
				if (session_id==''){
					$('#sessionid').attr('style','border:1px solid red !important;');									
					$('.requiredv').remove();
					$('#sessionid').before('<span class="text-danger requiredv">Required !</span>');
					return false;					
				}

				var $this = this;
				this.loaderState = true;
				this.axios.post(this.baseApiUrl+'users/by_search',this.createFormData({session_id:session_id,department_id:department_id, role_id:role_id}), {headers:this.axiosHeader}).then(function(response, status, request) {        
                            if (response.status === 200) {                                     	
                               $this.users = response.data;
							   $this.loaderState = false;
							     setTimeout(function() {
						         	$this.dTable = $('#usertable').DataTable({
						         			destroy:true,
									    	pageLength : 5,
									    });
						         }, 500);
                            }else{
                            }
                        }, function(e) {                                	
                             if(e.response.status === 401 ){
                             	 localStorage.removeItem("LoggedUser");
                                location.href = "/logout";
                             }else{                               
                             }                                                                   
                        })  

			},
			swal_form: function(obj){	
				
				var $vm = this, html='';					    
						
					html = "<legend class='text-left mb-1 mt-3 pb-0 fs1 p-text-success'>Select Faculty</legend>"+
					  	$vm.facultiesHTML+		
					  	"<legend class='text-left mb-1 mt-3 pb-0 fs1 p-text-success'>Select Department</legend>"+
					  	"<select id='department_id' name='department_id' class='form-control w-100'></select>"+									  	
					  	"<legend class='text-left mb-1 mt-3 pb-0 fs1 p-text-success'>Select Role</legend>"+
					  	"<select class='swal2-input' id='role_id'>";
					  	for(var ik in JSON.parse(this.roles)){
					  		html += "<option value='"+JSON.parse(this.roles)[ik]+"'>"+ik+"</option>";
					  	}					  	
					  	html += "<legend class='text-left mb-1 pb-0 fs1 p-text-success'>Select csv file</legend>"+					     
					    '<input id="swal-file1" type="file" class="mt-1 mx-auto"  >' ;	

					
					$('#system-loader').hide();						
					Swal.fire({
					  title: "Upload Bulk User",
					  html:html,
					  focusConfirm: false,
					  cancelButtonText:'Cancel',				      				      
				      cancelButtonColor:'#dd000f',					      
				      confirmButtonColor:'#00b96b',					      
				      showCancelButton:true,
					  preConfirm: () => {
					  	var faculty = document.getElementById('faculty_id').value,
					  		department = document.getElementById('department_id').value,
					  	 	//Validate whether File is valid Excel file.
        					 fileUpload = document.getElementById("swal-file1"),
        					 role_id = document.getElementById("role_id").value,
        					 csv = document.getElementById("swal-file1").files[0]        					
        					 var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx|.csv)$/;
        					if (regex.test(fileUpload.value.toLowerCase())) {					     
					        } else {
					           Swal.showValidationMessage('Error: please select a valid file (.csv file)');
					        }

					  	if ( faculty == "" || department == "") {					     
					         Swal.showValidationMessage('All fields are required');
					  	}
	
					    return [
					      faculty,
					      department,
					      csv,
					      $( "#faculty_id option:selected" ).text(),
					      $( "#department_id option:selected" ).text(),					     
					      role_id
					    ]
					  } 
					}).then((result)=>{
						if (result.value) {
					    const answers = {faculty_id:result.value[0], user_name:result.value[1], csv:result.value[2],role_id:result.value[5]}
					    Swal.fire({
					      title: 'Click on Proceed to Continue',
					      text: 'you can also click on cancel to restart',
					      html: `faculty: ${result.value[3]}<br>Department: ${result.value[4]}`,					     
					      confirmButtonText:'Proceeed',					      
					      cancelButtonText:'Cancel',					      
					      showCancelButton:true,					      
					      showLoaderOnConfirm: true,
					      confirmButtonColor:'#00b96b',	
					       preConfirm: (login) => {			
					        	var formData=new FormData();
								formData.append("csv",result.value[2]);
								formData.append("department_id", result.value[1]);
								formData.append("faculty_id", result.value[0]);
								formData.append("role_id", result.value[5]);	
								var resultR='';							
					        	$vm.axios.post($vm.baseApiUrl+'users/import_students',formData,{headers:$vm.axiosHeaderWithFiles})
						      	.then(response => {							      		      						      		
							        resultR=response;
							        if (!response.data.sucess) {
							          throw new Error(response.statusText)
							        }		
							        //$vm.response = 'response';
							     //   return response.json()
						      	})
						      	.catch(error => {
							      	if (error.response) {
								      	if (error.response.status == 409) {
									        Swal.showValidationMessage(
									          `Failed: user Already Exist`
									        )						      		
								      	}else if(error.response.status == 401){
								      		location.reload();
								      	}else{
								      		Swal.showValidationMessage(
									          `Failed: Something went wrong`
									        )						      		
								      	}
							      	}
						      	})			
						      	let interval = setInterval(function(){
						      		if (resultR!= ''){
						      			$vm.response = resultR;
								    	clearInterval(interval);					      		
								    	return [
								    	resultR,
								    	120
								    	]	
						      		}
						      	},50);		    		   
						  },
						  allowOutsideClick: () => !Swal.isLoading()
					    }).then((result) => {
					    	let interval = setInterval(function(){
					      		if ($vm.response != ''){
							    	clearInterval(interval);					      		
							    	let html = `<div>
					    					<button class="button btn-info py-2 px-3 mb-2" id="PrintAreaUXP">Print</button>
					    					<div id="resultuploadidX" style="overflow-y:scroll; height:250px;">
					    					<table class="table table-stripped table-hover">
					    				<thead>
					    					<th class="bg-success fs1 text-white p-1 w-50">uploaded</th>
					    					<th class="bg-danger fs1 text-white p-1 w-50">Error: already exists</th>
					    				</thead>
					    				<tbody>							    				
							    	`;

							    	let uploaded = $vm.response.data.uploaded,failed = $vm.response.data.failed, maxRow =  Math.max(uploaded.length,failed.length),
							    	tr='';
							    	for(let k= 0; k<maxRow; k++){
							    		tr += '<tr>';
							    		if (k< uploaded.length) {
								    		tr += '<td class="p-1 fs01">'+ uploaded[k] +'</td>'
							    		}else{tr += '<td class="p-1 fs01"></td>';}

							    		if (k< failed.length) {
								    		tr += '<td class="p-1 fs01">'+ failed[k] +'</td>'
							    		}else{tr += '<td class="p-1 fs01"></td>';}														    		
							    		tr += '</tr>';
							    	}
							    	setTimeout(function() {
							    		$('#PrintAreaUXP').click(function(){
							    			$vm.PrintArea('resultuploadidX','Result on user upload');
							    		})
							    	}, 100);
							    	html += tr +
							    			`</tbody></table></div> </div>`;							    	
									    Swal.fire({							    
									      title: 'Uploaded Result',				
									      html:	html,	      
									      icon: 'success',
									      confirmButtonText:'Ok',										      
				      			  		confirmButtonColor:'#00b96b',	
									    }).then((result)=>{
									    	location.reload();
									    })										    
							    	
					      		}
						      	},100);				    	
					    	//let title = $vm.response.data.success;					    						    	
					    })
					  }
					})
				
					
				//let $vm = this;	
				
			

			},
			edituser:function(obj){				
				this.VueSweetAlert2('v-userform', {
					type:'',
					update:true,
					faculties: this.faculties,
					departments: this.departments,
					roles:this.roles,
					alldata: obj
				});				
			},
			deleteuser: function(id){
				Swal.fire({
					title: 'confirm delete',
					icon:'warning',
					confirmButtonText:'Continue',					      
					cancelButtonText:'Cancel',				      				      
					cancelButtonColor:'#dd000f',					      
					confirmButtonColor:'#00b96b',					      
					showCancelButton:true,					      
					showLoaderOnConfirm: true,
				}).then((result)=>{
					if (result.value) {
						this.axiosDelete('api/users/delete',{'user_id': id})					
					}
				})					
			},
			singleValidate: function(id){
				$('#'+id).css('border','1px solid #e45');
				$('.requiredv').remove();
				$('#'+id).after('<span class="text-danger requiredv">Required !</span>')
			},
			uploadstudent: function(){					
				this.swal_form(false, null);					
					    var $this = this;
					    setTimeout(function() {
					    	$('#faculty_id').change(function(){
					    		
					    		var departmentsX = $this.faculties.filter((item)=>{return item.id === $(this).val()})[0].department;
					    		var opt="";
					    		departmentsX.forEach((item, idex)=>{
										opt += "<option value='"+item.id+"'>"+ item.code +"</option>";
								});	

					    	$('#department_id').html(opt);
								
							})
					    }, 50);
			},
			async axiosGetFacultyHtml(update, faculty_id){
				//method 1 
				//it relies on only the first faculties fetched
				//require page reload in other to get faculty update
				//it increase speed
				if (this.faculties === null) {
					this.faculties =  await this.axiosGet('api/faculties/faculties');						
				}

				//method 2 
				//does not require page reload 
				//ajax request is made every time
				//it might slow down operation
				/*this.faculties =  await this.axiosGet('api/faculties/faculties');*/

				this.facultiesHTML = "<select id='swal-input0' class='swal2-input mt-1'>"
				this.faculties.forEach((item, idex)=>{
					if (update) {						
						this.facultiesHTML += "<option value='"+item.id+"'";
						if (item.id == faculty_id){
							this.facultiesHTML += "selected=selected";
						}
						this.facultiesHTML += ">"+ item.code +"</option>";
					}else{
						this.facultiesHTML += "<option value='"+item.id+"'>"+ item.code +"</option>";
					}
				});
				this.facultiesHTML += "</select>";						
				this.watchfacultyHtml.value = Math.random(1,1000);
				
			 	
			},
			async tableInitializer(id, url, searchUrl="", searchData=""){		
				let $this = this;	
				this.loaderState = true;	
				this.usersloaderState = true;	
				
				if(searchData == ''){
					this.users = await this.axiosGet(`${url}`);					
				}else{
					this.users = await this.axiosGet(`${searchUrl}/${searchData}`);					
				}		
				setTimeout(function() { 			
					$this.table = $("#"+id).DataTable({    
					destroy:true,        
					pageLength: 5,       
					stateSave: true,      
					dom: "lBfrtip",   
					drawCallback: function (settings) {		
						var api = this.api();					
						var info = api.page.info();   
						if(info.page + 1 === info.pages && $this.users.current_page !== $this.users.last_page) {              
							$(".paginate_button.next").removeClass('disabled');
							$('.paginate_button.next', $(".paginate_button.next").parent())          
							.on('click', function(){                                          
							$this.tableInitializer(id,$this.users.next_page_url);      
							
							});                 
						} else {

						}
						if(info.page === 0 && $this.users.current_page !== 1) {                                            
							$(".paginate_button.previous").removeClass('disabled');
							$('.paginate_button.previous',$(".paginate_button.previous").parent())          
							.on('click', function(){
								$this.tableInitializer(id,$this.users.prev_page_url);                  
							});       
						} else {
						}
					},
					infoCallback:function(){                    
					return 'Page '+ ($this.users.from) +' of '+ $this.users.total;                  
					},         
					buttons:[
					{
						extend: 'csv',
						text:'Excel',
						exportOptions: {
						columns: ':visible'
						},
					}
					]
					})
					$this.loaderState = false;
					$(".dataTables_filter label input").attr('title','press enter to search')
					$(".dataTables_filter label").on('keyup','input', function(e){
						console.log(e.keyCode, e)						
						if(e.keyCode == 13) {							
							let val= $(".dataTables_filter label input").val();				
							if(val !=='')
							$this.tableInitializer(id,url,searchUrl, val)
						}
					})
				},1000)
				$this.usersloaderState = false;	

			
			},
			async tableInitializer2(id, url, searchUrl="", searchData=""){		
				let $this = this;	
				this.loaderState = true;	
				this.usersloaderState2 = true;	
				
				if(searchData == ''){
					this.inactiveUser = await this.axiosGet(`${url}`);					
				}else{
					this.inactiveUser = await this.axiosGet(`${searchUrl}/${searchData}`);					
				}		
				setTimeout(function() { 			
					$this.table2 = $("#"+id).DataTable({    
					destroy:true,        
					pageLength: 5,       
					stateSave: true,      
					dom: "lBfrtip",   
					drawCallback: function (settings) {		
						var api = this.api();					
						var info = api.page.info();   
						if(info.page + 1 === info.pages && $this.inactiveUser.current_page !== $this.inactiveUser.last_page) {              
							$(".paginate_button.next").removeClass('disabled');
							$('.paginate_button.next', $(".paginate_button.next").parent())          
							.on('click', function(){                                          
							$this.tableInitializer(id,$this.inactiveUser.next_page_url);      
							
							});                 
						} else {

						}
						if(info.page === 0 && $this.inactiveUser.current_page !== 1) {                                            
							$(".paginate_button.previous").removeClass('disabled');
							$('.paginate_button.previous',$(".paginate_button.previous").parent())          
							.on('click', function(){
								$this.tableInitializer(id,$this.inactiveUser.prev_page_url);                  
							});       
						} else {
						}
					},
					infoCallback:function(){                    
					return 'Page '+ ($this.inactiveUser.from) +' of '+ $this.inactiveUser.total;                  
					},         
					buttons:[
					{
						extend: 'csv',
						text:'Excel',
						exportOptions: {
						columns: ':visible'
						},
					}
					]
					})
					$this.loaderState = false;
					$(".dataTables_filter label input").attr('title','press enter to search')
					$(".dataTables_filter label").on('keyup','input', function(e){
						//console.log(e.keyCode, e)						
						if(e.keyCode == 13) {							
							let val= $(".dataTables_filter label input").val();				
							if(val !=='')
							$this.tableInitializer(id,url,searchUrl, val)
						}
					})
				},1000)
				$this.usersloaderState2 = false;				
			}

		},
		async created(){			
			this.faculties       =  await this.axiosGet('api/faculties/faculties');						
			this.departments	 = await  this.axiosGet('api/departments/departments');					
					
			this.sessions        = await this.axiosGet('api/session/all_session');			
			this.facultiesHTML   = this.selectHtmlGen(this.faculties,'code','faculty_id' )							
			this.departmentsHTML = this.selectHtmlGen(this.departments,'code','department_id' )							
			this.tableInitializer('usertable','api/users/users','api/users/search_users');
			this.tableInitializer2('usertable2','api/users/inactive_users','api/users/search_inactive_users');
						
			    /*initialize datatable */
			   // var $this = this;
	        /* setTimeout(function() { */
	         	/* $this.dTable = $('#usertable').DataTable({										
					responsive:true,
					pageLength: 6,			    						
					drawCallback: function (settings) {
						let json = settings.json.data
						
						$("#usertable tbody").find('tr td:last-child').each(function(index){
							$(this).prev().prev().prev().css('text-transform','lowercase')
							
							$(this).html(`
							<span class="ml-2 fa fa-edit pl-3 fedit  fs01 cursor-1" style="border-left: 1px solid #ccc;"></span>
							<span class="ml-2 fa fa-trash pl-3 fdelete fs01 cursor-1" ></span>`);
							$(this).on("click", ".fedit", function(){
								$this.VueSweetAlert2('v-userform', {
								type:'',
								update:true,
								faculties: $this.faculties,
								departments: $this.departments,
								roles:$this.roles,
								alldata: json[index]
							}); 	
							})
							$(this).on("click", ".fdelete", function(){
								Swal.fire({
									title: 'confirm delete',
									icon:'warning',
									confirmButtonText:'Continue',					      
									cancelButtonText:'Cancel',				      				      
									cancelButtonColor:'#dd000f',					      
									confirmButtonColor:'#00b96b',					      
									showCancelButton:true,					      
									showLoaderOnConfirm: true,
								}).then((result)=>{
									if (result.value) {
										$this.axiosDelete('api/users/delete',{'user_id': json[index].id})					
									}
								})		
							}); 	
							
						})							
						$this.usersloaderState = false;							
					}
			    });					
				 *//* $this.dTable = $('#usertable0').DataTable({
			    	pageLength : 5,
			    });	 
	          }, 50);  */
			
		},
		 props:{
          	roles:{
          		type:String,
          		default:function(){
          			return '{}'
          		}
          	}
          },
		mounted(){			
			
			this.$nextTick(function(){ 		
				setTimeout(() => {
					$( "#forInActive" ).hide();				
				}, 4000);		
				var $this = this;

				$(document).click(function(e){
					if (!$(e.target).hasClass("Menux")) 
					{                	
						$this.hideMenu();
            		}
				});

				function PrintArea(elem, title,length=400)
				{
				    var mywindow = window.open('', 'PRINT', 'height='+length+',width=600');

				    mywindow.document.write('<html><head><title>' + title  + '</title>');
				    mywindow.document.write('</head><body >');
				    mywindow.document.write('<h1>' + title  + '</h1>');
				    mywindow.document.write(document.getElementById(elem).innerHTML);
				    mywindow.document.write('</body></html>');

				    mywindow.document.close(); // necessary for IE >= 10
				    mywindow.focus(); // necessary for IE >= 10*/

				    mywindow.print();
				    mywindow.close();

				    return true;
				}
			}); 
		}
	};
</script>
<style type="text/css">
	tr td{
		text-transform: capitalize;
	}
	code{
		text-transform: lowercase ;		
	}	
	thead tr th{
		background: white;
	}
	table.dataTable.no-footer{
		border-bottom: none;
	}
	table thead tr th{
		/* box-shadow: 0px 1.5px 5px #bbb; */
	}
#forInActive, #forActive{
	top:20px;
	position:absolute;
	box-shadow: 7px 10px 16px #ccc;
    padding: 13px !important;
    border-radius: 0px;
    width: 100%;
}
.display-none{
	display: none;
}
.btn-width-179{
	width:179px;
}
.w-179{
	width:179px;	
}
.r-0{
	border-radius: 0px !important;
}
.r-t-0{
	border-radius: 0px 0px 4px 4px !important;
}
  @media (max-width: 768px  ) {
      .btn-width-179{
        width: 100% !important;
      } 
}
</style>