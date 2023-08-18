<template>
	<div>

		<v-loader v-if="loaderState"></v-loader>
		<div v-show="!loaderState" class="v-scroll-x">
			<table id="coursetable" class="table table-hover">
				<thead>
					<tr id="cheadV">

						<th width="5%">Img</th>
						<th width="25%">Title</th>
						<th width="10%">Code</th>
						<th width="25%">Description</th>
						<th width="25%">Experiments</th>
						<!-- <th width="15%">Instructors</th> -->
						<th width="10%">Actions</th>
					</tr>
				</thead>
				<tbody v-if="tableLoaded">
					<tr v-for="(course) in createdCourses" :key="course.id" :data-counter="counter = 0">
						<td width="5%">
							<label class="container">
								<input type="checkbox">
								<span class="checkmark"></span>
							</label>
						</td>
						<td width="25%" :title="course.title">{{ course.title.slice(0, 20) }} ...</td>
						<td width="10%">{{ course.code }}</td>
						<td width="25%" :title="course.description">{{ course.description.slice(0, 20) }} ...</td>
						<td width="25%">
							<span v-for="(course_experiment, index ) in course.course_experiment" :key="index + '_xc'">
								<a :class="index > 1 ? 'experiment bullets text-primary line-h1' : 'bullets line-h01 text-primary'"
									href="#"
									@click="checktaskcreated(course_experiment.experiment.id, course_experiment.experiment.page)">
									{{ course_experiment.experiment.name }}</a>
							</span>
						</td>
						<!-- <td width="15%">500L</td>	 -->
						<td width="10%">

							<span title="edit" class="ml-2 fa fa-edit pl-3  fs01 cursor-1"
								@click="editCourse(createdCourses[index])" style="border-left: 1px solid #ccc;"></span>
							<span title="delete" class="ml-2 fa fa-trash pl-3  fs01 cursor-1"
								@click="deleteCourse(course.id)"></span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</template>

<script>

export default {

	data() {
		return {
			createdCourses: null,
			tableLoaded: false,
			loaderState: true,
			counter: 0,
			weeklyworks: [],
		}
	},
	methods: {

		//<createcourse update='${true}' alldata='${[]}'></createcourse>
		async checktaskcreated(id, pagename, course = {}) {
			console.log(this.weeklyworks, id);

			if (this.weeklyworks.length > 0) {
				const foundWork = this.weeklyworks.find(work => work.experiment_id === id);

				if (foundWork) {
					location.href = `/${pagename}/${foundWork.id}`;
				} else {
					await this.showNoTaskWarning();
				}
			} else {
				await this.showNoTaskWarning();
			}
		},

		async showNoTaskWarning() {
			const swalConfig = {
				title: 'No Task Created for this Experiments',
				text: 'You have not created any Task',
				icon: 'warning',
				showDenyButton: false,
				showCancelButton: true,
				confirmButtonColor: '#00b96b',
				cancelButtonColor: '#d33',
				confirmButtonText: `Goto Manage Task`,
			};

			const result = await Swal.fire(swalConfig);

			if (result.isConfirmed) {
				location.href = "/manage-task";
			}
		},

		editCourse: function (obj, id) {
			this.VueSweetAlert2('v-createcourse', {
				update: true,
				alldata: obj,
				course_id: 2 //id
			})
		},
		deleteCourse: function (id) {
			this.axiosDelete('api/courses/delete', { course_id: id });
		}
	},
	async created() {

		this.createdCourses = await this.axiosGet('api/courses/courses_with_experiments'); //this endpoint is not returning foriegn data
		this.weeklyworks = await this.axiosGet('api/works/weekly_works_only'); //this endpoint is not returning foriegn data		    
		this.tableLoaded = true;


		let $this = this;
		/*initialize datatable */
		setTimeout(function () {
			$('#coursetable').DataTable({
				pageLength: 5,
			});
			$this.loaderState = false;
		}, 200);
	},
	mounted() {
		this.$nextTick(function () {
			$(document).ready(function () {

			})
		});
	}
};
</script>
<style type="text/css">
.bullets:before {
	content: '\f08e';
	font-size: 0.7em;
	color: blue;
	font-family: FontAwesome;
	display: block;
	position: relative;
	top: 15px;
	left: -15px;

}

.experiment {
	display: none;

}

td:hover>span>a.experiment {
	display: block !important;
}</style>