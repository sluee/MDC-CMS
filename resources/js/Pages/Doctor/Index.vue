<script setup>
import Sidebar from '@/Layouts/Sidebar.vue';
import Pagination from '@/Components/Pagination.vue';
import { ref, watch, onMounted } from 'vue';
import { Link, router, useForm, Head } from '@inertiajs/vue3';


    let showConfirm = ref(false)
    let selectedDoctorForDelete = null
    let selectedDoctor = null


    let form = useForm({
        lastname: '',
        firstname: '',
        status: '',
        email:'',
        gender: '',
        specialization: '',
        contact_no: '',
    })

    let props = defineProps({
        doctors: Array,
        user:Object,
        services:Object,
        activeDoctorsCount: Number,
        filters:Object
    })

    function closeModal(){
        showConfirm.value = false;
    }
    function edit(doc) {
    form.lastname = doc.lastname,
    form.firstname = doc.firstname,
    form.email =  doc.email,
    form.gender =  doc.gender,
    form.status = doc.status,
    form.specialization = doc.qty
    form.contact_no = doc.qty
    selectedDoctor = doc
    }

    function remove(doc) {
        selectedDoctorForDelete = doc
        showConfirm.value = true;
    }

    const submit = () =>{
        if(selectedDoctor) {
            form.put('/doctor/' + selectedDoctor.id)

            }else {
            form.post('/doctor')
        }
    }
    //const inactiveDoctors = ref([]);
    let search = ref(props.filters.search);
    watch(search, (value) => {
        router.get(
            "/doctor",
            { search: value },
            {
                preserveState: true,
                replace: true,
            }
        );
    });

    onMounted(() => {
    // Set a timeout to hide the success flash message after 3 seconds
        const successFlashMessage = document.getElementById('flash-success-message');
            if (successFlashMessage) {
                setTimeout(() => {
                successFlashMessage.style.display = 'none';
                }, 3000);
        }

        // Set a timeout to hide the error flash message after 3 seconds
        const errorFlashMessage = document.getElementById('flash-error-message');
            if (errorFlashMessage) {
                setTimeout(() => {
                errorFlashMessage.style.display = 'none';
            }, 3000);
        }
    });

</script>

<template>
    <Sidebar>
        <Head title="Doctor"/>
        <div v-if="$page.props.flash.success" id="flash-success-message" class="absolute top-20 right-1 p-4 bg-green-300 border border-gray-300 rounded-md shadow-md">
            {{ $page.props.flash.success }}
            <div class="progress-bar success"></div>
        </div>

        <div v-if="$page.props.flash.error" id="flash-error-message" class=" absolute top-20 right-1 p-4 bg-red-300 border border-gray-300 rounded-md shadow-md">
            {{ $page.props.flash.error }}
            <div class="progress-bar error"></div>
        </div>
        <section class="container px-4 py-6 mx-auto">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-x-3">
                        <h2 class="text-3xl font-bold text-black">List of Doctors</h2>

                        <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full">{{ activeDoctorsCount }} doctors</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 md:flex md:items-center md:justify-between md:w-full">
                <div class="inline-flex overflow-hidden">
                    <div class="py-3 px-4">
                        <div class="relative max-w-xs">
                            <label for="hs-table-search" class="sr-only">Search</label>
                            <input type="search" v-model="search"  name="hs-table-search" id="hs-table-search" class="py-2 px-3 ps-9 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none " placeholder="Search by name or id">
                            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                                <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative flex items-center md:mt-0">

                    <div class="flex items-center gap-x-3">
                        <a :href="route('doctor.pdf')" as="button" target="_blank" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white border rounded-lg gap-x-2 sm:w-auto hover:bg-blue-100 ">
                            <i class="fa-solid fa-download"></i>

                            <span>Export</span>
                        </a>

                        <Link href="/doctor/create" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600">
                            <i class="fa-solid fa-user-plus"></i>

                            <span>Add doctor</span>
                        </Link>
                    </div>
                </div>
            </div>

            <div class="w-full px-2">
                <div class="h-12">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <table class="min-w-max w-full table-auto">
                            <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">

                                    <th class="py-3 px-6 text-center">FullName</th>
                                    <th class="py-3 px-6 text-center">Specialization</th>
                                    <th class="py-3 px-6 text-center">Email</th>
                                    <th class="py-3 px-6 text-center">Contact No</th>
                                    <th class="py-3 px-6 text-center">Status</th>
                                    <th class="py-3 px-6 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light" >
                                <!-- <tr v-if="doctors.data.length === 0">
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-center">
                                        No doctor found.
                                    </td>
                                </tr> -->
                                <tr  class="border-b border-gray-200 hover:bg-gray-100" v-for="doc in doctors.data" :key="doc.id">
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium">{{ doc.user.firstname }} {{ doc.user.lastname }}</p>
                                        </div>
                                    </td>

                                    <td class="py-3 px-6 text-center">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium">{{ doc.specialization }}</p>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium">{{ doc.user.email }}</p>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium">{{ doc.user.contact_no }}</p>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex items-center justify-center" >
                                            <p class="font-medium">

                                                <span class="remarks-cell  py-1 px-3 rounded-full text-xs"
                                                :class="{
                                                    'bg-red-200 text-red-600': doc.user.status == 0,
                                                    'bg-green-200 text-green-600':doc.user.status == 1,

                                                }"
                                            >{{ doc.user.status ? 'Active' : 'Inactive' }}</span>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex item-center justify-center">
                                            <div class="flex item-center justify-center">
                                                <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                                    <Link :href="'/doctor/show/'+ doc.id" title="Show Details">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </Link>
                                                </div>
                                            </div>
                                            <div class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110">
                                                <Link :href=" '/doctor/edit/' + doc.id" class="btn" title="Edit Doctor">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </Link>
                                            </div>
                                            <div class="w-4  ml-2 mr-2 transform hover:text-red-500 hover:scale-110">
                                                <Link href="#" @click="remove(doc)" class="btn" title="Delete Doctor">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </Link>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-show="doctors.data.length < 1" class="flex flex-col w-full mt-9">
                        <h1 class="text-center text-xl text-gray-400 mb-6">No doctor found</h1>
                        <!-- <img src="../../Components/images/no-result.png" alt="no result" class="w-[250px] opacity-25 mx-auto"> -->
                    </div>

                    <!-- Paginator -->
                    <div class="flex justify-between">
                        <div class="mt-2" v-if="doctors.data.length > 0">Showing page {{ doctors.current_page }} of {{ doctors.last_page }}</div>
                        <Pagination v-if="doctors.data.length > 0" :links="doctors.links" class="mt-6"/>
                    </div>
                </div>
            </div>
        </section>
    </Sidebar>
</template>
<style scoped>

#flash-success-message {
    animation: fadeOut 6s ease-in-out forwards;
}

.progress-bar {
    height: 5px;
    width: 100%;
    background-color: #4CAF50; /* Green color */
    animation: progressBar 3s linear;
}
#flash-error-message {
    animation: fadeOut 7s ease-in-out forwards;
}

.success .progress-bar {

    animation: progressBar 5s linear;
}
.error .progress-bar {
    background-color: #FF5733; /* Red color */
    animation: progressBar 5s linear;
}

@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
    }
}

@keyframes progressBar {
    0% {
        width: 100%;
    }
    100% {
        width: 0;
    }
}
</style>
