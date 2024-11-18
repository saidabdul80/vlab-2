<template>
	<div style="display: flex; justify-content: space-between; background: var(--sys-primary); position: relative; z-index: 10; width: 100%; height: 37px;">		
		<span style="width: 30%; display: flex; flex-wrap: wrap; align-items: center;">			
			<span class="fa fa-chevron-right text-dark ml-4 pl-2 tbtn" v-if="btnState" @click="toggleExperimentGuider"></span>
			<span class="fa fa-chevron-left text-dark ml-4 tbtn" @click="toggleExperimentGuider" v-else></span>
		</span>
		<div style="display: flex; justify-content: space-between; background: var(--sys-primary); width: 70%; color: white;">
			<div style="padding: 6px;">				
				<span class="fa fa-flask fa-ico activeIco" @click="toggleRightNav" rel="tools"></span>
				<span class="fa fa-table fa-ico" @click="toggleRightNav" rel="resulttable"></span>
				<span class="fa fa-area-chart fa-ico" @click="toggleRightNav" rel="resultgraph"></span>
				<span class="fa fa-question-circle-o fa-ico" @click="toggleRightNav" rel="userhelp"></span>
				<span class="fa fa-file-text-o fa-ico" @click="toggleRightNav" rel="tools"></span>
			</div>
			<div style="padding: 6px; margin-right: 60px;" v-if="startExperiment">				
				<span @click.stop="submit('test')" v-if="mode === 'test'" class="fa fa-save fa-ico"></span>				
				<span @click.stop="submit('test')" v-if="mode === 'practice'" class="text-white submit">Submit</span>				
			</div>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			btnState: true,
			startExperiment: false,
			resultData: null,
			weekly_work_exp_id: null,
			timeStart: 0,
			timeleft: 0
		};
	},
	methods: {
		fillResultData() {
			const payload = {
				user_id: this.currentUser.id, // Assuming `currentUser.id` holds the user ID
				weekly_work_id: this.weekly_work_exp_id, // The weekly work experiment ID
			};

			// Send a POST request to fetch the saved result
			this.axios.post('api/experiments/student_experiment_result', payload, { headers: this.axiosHeader })
				.then(response => {
					// Parse the result JSON data
					const resultData = JSON.parse(response.data.result_json || "[]");
					
					// Call the method to update the table with the result data
					this.updateResultTable(resultData);
				})
				.catch(error => {
					console.error("Error fetching the student experiment result:", error);
				});
		},

		// Update the result table with the fetched result data
		updateResultTable(resultData) {
			// Check if the resultData is available and it's an array
			if (Array.isArray(resultData)) {
				// Empty the current resultData array before populating it
				this.resultData = [];

				// Iterate through the result data and fill the table
				resultData.forEach((dataItem, index) => {
					// Create an object to hold the data for each result section
					const tableRowData = {
						title: dataItem.title, // The title of the result (e.g., section name)
						head: dataItem.head, // Headers for the table (if any)
						mhead: dataItem.mhead, // Table headers HTML content (if needed)
						data: dataItem.data, // Actual data for the table rows
					};

					// Insert the data into the `resultData` array
					this.resultData.push(tableRowData);
				});

				// Call your method to actually fill the table on the UI
				this.renderResultTable();
			}
		},

		// Render or re-render the table with the updated `resultData`
		renderResultTable() {
			let xindex = -1;
			let inputs = $('#result_table').find('.resultReading'); // Get all the input fields

			$('#result_table').find('.main_result_table').each((index, element) => {
				const resultData = this.resultData[index];
				
				if (resultData) {
					resultData.data.forEach((rowData, rowIndex) => {
						rowData.forEach((cellData, cellIndex) => {
							xindex += 1;

							const inputField = $(element).find('.resultReading').eq(xindex); 

							inputField.val(cellData);
						});
					});
				}
			});
		},
		toggleRightNav(e) {
			$('.fa-ico').removeClass('activeIco');
			e.target.classList.add('activeIco');
			this.$eventBus.$emit('rightNavtoggleClick', { text: e.target.getAttribute("rel") });
		},
		
		storeData(result = '', time_submitted = '', msg = false, fortimer = 1) {
			const formObj = {
				user_id: this.currentUser.id,
				weekly_work_exp_id: this.weekly_work_exp_id,
				result_json: result,
				time_started: this.timeStart,
				time_submitted: time_submitted,
				time_left: this.timeleft,
				fortimer: fortimer,
			};
			if (msg) {
				this.axiosGetByParamsWithMessage('api/experiments/save_experiment_result', formObj, this, 'Saved!');
			} else {
				this.axios.post('api/experiments/save_experiment_result', this.createFormData(formObj), { headers: this.axiosHeader });
			}
		},
		toggleRightNav2(e) {
			$('.fa-ico').removeClass('activeIco');
			$('.fa-ico').each(function () {
				if ($(this).attr('rel') === e) {
					$(this).addClass('activeIco');
				}
			});
			this.$eventBus.$emit('rightNavtoggleClick', { text: e });
			this.$eventBus.$emit('toggleRightNav2', { text: e });
		},
		toggleExperimentGuider() {
			this.btnState = !this.btnState;
			this.navState = !this.navState;
			this.$eventBus.$emit('toggleClick', { text: this.navState });
		},
		updateResultData() {
			this.resultData = [];
			$('#result_table').find('.main_result_table').each((index, element) => {
				const resultData = {
					title: $(element).prev().text(),
					head: [],
					mhead: $(element).find('thead').html(),
					data: [],
				};

				$(element).find('th').each((index2, th) => {
					resultData.head.push($(th).text());
				});

				$(element).find('tr').each((index2, tr) => {
					const bdy = [];
					let emptyChk = '';
					$(tr).find('.resultReading').each((index3, input) => {
						const value = $(input).val();
						bdy.push(value);
						if (index3 !== 0 && value !== '') {
							emptyChk = 1;
						}
					});
					resultData.data[index2] = bdy;
				});

				this.resultData.push(resultData);
			});
		},
		submit(a) {
			this.updateResultData();
			let emptyChk = '';
			this.resultData.forEach(resultData => {
				resultData.data.forEach(row => {
					if (row.slice(1).some(cell => cell !== '')) {
						emptyChk = 1;
					}
				});
			});

			if (!emptyChk) {
				Swal.fire({ title: 'Please enter your data' }).then(result => {
					if (result.value) {
						this.toggleRightNav2('resulttable');
					}
				});
				return;
			}

			Swal.fire({
				title: 'Are you sure you want to submit?',
				html: `
					<div>
						<input type="checkbox" id="finalSubmissionCheckbox">
						<label for="finalSubmissionCheckbox">Final Submission</label>
					</div>
				`,
				showCancelButton: true,
				confirmButtonText: 'Yes',
				cancelButtonText: 'No',
				cancelButtonColor: '#666'
			}).then(result => {
				if (result.value) {
					const isForTimer = !document.getElementById('finalSubmissionCheckbox').checked;
					const finalSubmissionParam = isForTimer ? 1 : 0;
					if (this.weekly_work_exp_id != null) {
						this.storeData(JSON.stringify(this.resultData), new Date().toLocaleString(), true, finalSubmissionParam);
					}
				}
			});
		}
	},
	props: {
		mode: {
			type: String,
			default: 'practice'
		}
	},
	mounted() {
		this.$nextTick(function () {
			setInterval(() => {
				this.updateResultData();
				if (this.startExperiment) {
					this.storeData(JSON.stringify(this.resultData));
				}
			}, 10000);
		});
	},
	created() {
		const pathname = location.pathname.split('/');
		this.weekly_work_exp_id = pathname[pathname.length - 1];
		
		this.$eventBus.$on('listeningToTimeLeft', data => {
			this.timeleft = data;
		});

		this.$eventBus.$on('startExperiment', () => {
			this.startExperiment = true;
			this.timeStart = new Date().toLocaleString();
			this.fillResultData(); 
		});
	},
	beforeDestroy() {
		this.$eventBus.$off('toggleClick', this.toggleNavOnHover);
	},
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap');
.tbtn:active {
	box-shadow: inset 1px 2px 3px #111;
}
.tbtn {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	width: 25px;
	height: 25px;
	border-radius: 50%;
	background: #eee;
	cursor: pointer;
}
.fa-ico {
	margin: 0px 5px;
	padding: 2px 5px;
	border-radius: 5px;
	color: #eee;
	font-size: 1.2em;
	cursor: pointer;
}
.fa-ico:hover {
	background: #EBEAEF;
	color: #2F274E;
}
.activeIco {
	background: #EBEAEF;
	color: #2F274E;
}
.submit:hover {
	color: #00b96b;
}
.submit:active {
	font-weight: bold;
}
.submit {
	cursor: pointer;
	user-select: none;
}
</style>
