<template>
	<div class="px-5 mt-0 pt-5 card-cont" style="position: relative;">
	  <div style="position: absolute; top: 10px; z-index: 100; text-transform: uppercase;" class="font2 fs1">
		::Courses
		<span v-if="section == 1">
		  <i class="fa fa-angle-double-right"></i>
		  {{ selectedCourse.code }}
		</span>
	  </div>
	  <select v-model="selectedSession" @change="selectSession" class="form-control col-lg-3 col-md-4">
		<option v-for="session in sessions" :value="session.id" :key="'x_' + session.id">{{ session.session }}</option>
	  </select>
	  <div v-if="section != 0" class="mb-2" style="position: fixed; top: 167px; z-index: 101; display: flex;">
		<button v-show="stepBack == 1" class="button bg-dark text-white px-1 py-1 pr-3" @click="sectionControl">
		  <span class="fa fa-chevron-left ml-2 mr-1 text-white fs01"></span>Back
		</button>
		<button v-show="stepBack == 2" class="button bg-success text-white px-1 py-1 pr-3" @click="viewStudentBtn">
		  <span class="fa fa-chevron-left ml-2 mr-1 text-white fs01"></span>Back
		</button>
	  </div>
	  <div v-if="loaderState">
		<v-loader></v-loader>
	  </div>
	  <div v-if="section == 0" class="row mt-4 w-100">
		<div class="col-lg-4 col-md-6 col-sm-12 p-3" v-for="(course, index) in courses" :key="index + '_pl'">
		  <div @click="selectCourse(course)" class="shadow-sm r2 button bg-white d-flex flex-wrap justify-content-between w-100 pl-0 py-4 pr-4">
			<div class="p-0 image-bg-1"></div>
			<div class="my-2">
			  <p class="fs2 fw7 mb-2 mt-4 fs10 font2 p-text-dark line-height-none show-student-detail cursor-1">{{ course.course_student?.length }}</p>
			  <p class="mt-0 pt-0 p-text-dark font2 fw4 fs2 pl-2">Students</p>
			  <div class="font fs2 mt-4 p-text-warning cursor-1 show-course-detail r1">
				<span class="fa fa-cube"></span>
				<span>{{ course.code }}</span>
			  </div>
			</div>
			<div>
			  <span class="fa fa-plus p-2 r2 mr-3 p-text-warning p-transparent-warning fs9"></span>
			</div>
		  </div>
		</div>
	  </div>
	  <div v-if="section == 0">
		<div v-if="courses?.length < 1" style="display: flex; flex-wrap: wrap; justify-content: center; align-items: center;">
		  <h3 style="color: #bbbbbc;" class="font">No Course Has Been Created</h3>
		</div>
	  </div>
	  <div v-if="section == 1">
		<v-viewstudentbycourse :course="selectedCourse" :session="selectedSession"></v-viewstudentbycourse>
	  </div>
	</div>
  </template>
  
  <script>
  import studentincourse from './studentInCourse.vue';
  import loader from './skeletalLoaderA.vue';
  
  export default {
	components: {
	  'v-viewstudentbycourse': studentincourse,
	  'v-loader': loader,
	},
	data() {
	  return {
		loaderState: true,
		section: 0,
		selectedCourse: null,
		courses: [],
		stepBack: 1,
		selectedSession: "",
		sessions: [],
		coursestudents: {},
	  };
	},
	methods: {
	  async selectSession() {
		this.stepBack = 1;
		this.section = 0;
		await this.queryData(this.selectedSession);
	  },
	  selectCourse(course) {
		this.selectedCourse = course;
		this.section = 1;
	  },
	  sectionControl() {
		this.section--;
	  },
	  async viewStudentBtn() {
		this.$eventBus.$emit('viewstudentBtn2', { data: true });
		this.stepBack = 1;
	  },
	  async queryData(session_id) {
		try {
		  this.loaderState = true;
		  const res = await this.axiosGet(`api/courses/courses_with_students/${session_id}`);
		  this.courses = res;
		  this.loaderState = false;
		  if (this.courses?.length < 1) {
			Swal.fire({
			  title: 'No Course Found',
			  text: 'You have not created any course',
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#00b96b',
			  cancelButtonColor: '#d33',
			  confirmButtonText: `Goto Create Course`,
			}).then((result) => {
			  if (result.isConfirmed) {
				location.href = "/create-course";
			  }
			});
		  }
		} catch (error) {
		  console.error(error);
		}
	  },
	},
	async created() {
	  this.$eventBus.$on('viewStudentExperiment', data => {
		this.stepBack = 2;
	  });
  
	  try {
		const res = await this.axiosGet('api/session/all_session');
		this.sessions = res;
		const currentSession = this.sessions.find(session => session.is_current);
		if (currentSession) {
		  this.selectedSession = currentSession.id;
		}
		await this.queryData(this.selectedSession);
	  } catch (error) {
		console.error(error);
	  }
	},
  };
  </script>
  
  <style scoped>
  .card-cont::-webkit-scrollbar-thumb {
	background: #20154f;
	border-radius: 10px;
	cursor: pointer;
  }
  
  .card-cont::-webkit-scrollbar-thumb:hover {
	background: #20154e;
	cursor: pointer;
  }
  
  .show-course-detail:hover {
	color: #dd8200;
	text-decoration: underline;
  }
  
  .show-student-detail,
  .show-course-detail {
	transition: all 0.2s;
  }
  
  .show-student-detail:hover {
	color: white;
	text-shadow: #20154e 0px 1px 3px;
  }
  
  button.button.btn:focus {
	outline: 0 !important;
	border: 0px solid transparent !important;
	box-shadow: 0 !important;
  }
  </style>
  