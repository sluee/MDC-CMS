<script setup>
    import Sidebar from '@/Layouts/Sidebar.vue';
    import Pagination from  '@/Components/Pagination.vue';
    import Modal from  '@/Components/Modal.vue';
    import DangerButton from '@/Components/DangerButton.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import { ref, onMounted, watch } from 'vue';
    import { useForm, Link, router } from '@inertiajs/vue3';
    import moment from 'moment';


    let showConfirm = ref(false)
    let selectedDispenseForDelete = null
    let selectedDispense = null


    let form = useForm({
        name: '',
        qty: '',
        med_id:''
    })

    let deleteForm = useForm({});

    let props = defineProps({
        dispenses: Array,
        medicine:Object,
        filters: Object
    })

    function edit(des) {
    form.name = category.name
    selectedCategory = category
    }

    function closeModal(){
        showConfirm.value = false;
    }

    function remove(des) {
        selectedDispenseForDelete = category
        showConfirm.value = true;
    }

    function deleteCat(){
        deleteForm.delete('/dispense/' + selectedDispenseForDelete.id)
        showConfirm.value = false;
    }

    const submit = () =>{
        if(selectedDispense) {
            form.put('/dispense/' + selectedDispense.id)
            form.name = "";
            form.med_id
            form.qty = "";
            }else {
            form.post('/dispense')
            form.name = "";
            form.med_id ="";
            form.qty = "";
        }
    }

    function formattedDate(date){
        return moment(date).format('MMMM   D, YYYY');
    }
    const errorMessage = ref('');

    // onMounted(() => {
    //   // Check if there are validation errors and set the error message
    //   if (this.$page.props && this.$page.props.errors) {
    //     const errorKeys = Object.keys(this.$page.props.errors);

    //     if (errorKeys.length > 0) {
    //       // Take the first error message of the first key
    //       errorMessage.value = this.$page.props.errors[errorKeys[0]][0];
    //     }
    //   }
    // });
    let search = ref(props.filters.search);
    watch(search, (value) => {
        router.get(
        "/dispense",
            { search: value },
            {
                preserveState: true,
                replace: true,
            }
        );
    });
</script>

<template>
    <Sidebar>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Medicine Dispense</h2>
            <div v-if="$page.props.flash.success" id="flash-success-message" class="absolute top-20 right-1 p-4 bg-green-300 border border-gray-300 rounded-md shadow-md">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash.error" id="flash-error-message" class=" absolute top-20 right-1 p-4 bg-red-300 border border-gray-300 rounded-md shadow-md">
                {{ $page.props.flash.error }}
            </div>
            <!-- <div v-if="errorMessage" id="flash-error-message" class=" absolute top-20 right-1 p-4 bg-red-300 border border-gray-300 rounded-md shadow-md">
                {{ errorMessage }}
            </div> -->
        </template>

        <div class="px-2 mt-5">
            <div class="flex -mx-2">
            <div class="w-1/3 px-2">
                <div class="h-12">
                    <div class="relative px-4 py-10 bg-white mx-8 md:mx-0 shadow rounded-3xl sm:p-10">
                        <div class="max-w-md mx-auto">
                          <div class="flex items-center space-x-5">
                            <div class="block pl-2 font-semibold text-xl self-start text-gray-700">
                              <h2 class="leading-relaxed">Medicine Dispense Form</h2>
                            </div>
                          </div>
                          <hr>
                          <div class="divide-y divide-gray-200">
                                <form class="" @submit.prevent="submit">
                                    <div class="py-5 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                                        <div class="flex flex-col">
                                            <label class="leading-loose">Name</label>
                                            <input type="text" v-model="form.name" id="name" class="px-4 py-2 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 rounded-md focus:outline-none text-gray-600">
                                            <div class="text-sm text-red-500 italic" v-if="form.errors.name">{{ form.errors.name }}</div>
                                        </div>
                                        <div class="flex flex-col">
                                            <label class="leading-loose">Medicine Name</label>
                                            <select id="med_id" name="med_id" v-model="form.med_id" class="pr-4 py-2 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 rounded-md focus:outline-none text-gray-600">
                                                <option value="" disabled>Select a medicine</option>
                                                <option v-for="m in medicine" :value="m.id" :key="m.id"> {{ m.name }}</option>
                                            </select>
                                            <div class="text-sm text-red-500 italic" v-if="form.errors.cat_id">{{ form.errors.cat_id }}</div>
                                        </div>
                                        <div class="flex flex-col">
                                            <label class="leading-loose">Quantity</label>
                                            <input type="number" v-model="form.qty" id="qty" class="px-4 py-2 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 rounded-md focus:outline-none text-gray-600">
                                            <div class="text-sm text-red-500 italic" v-if="form.errors.qty">{{ form.errors.qty }}</div>
                                        </div>
                                    </div>
                                        <div class="pt-4 flex items-center space-x-4">

                                            <button type="submit" class="bg-blue-500 flex justify-center items-center w-full text-white px-4 py-3 rounded-md focus:outline-none">Save</button>
                                        </div>
                                </form>
                          </div>
                        </div>
                      </div>
                </div>
            </div>
            <div class="w-3/4 px-2">
                <div class="h-12">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <!-- <div class="p-6 text-gray-900">You're logged in!</div> -->
                        <div class="mt-4 mb-3 mr-0 mb-0 ml-0 sm:mt-0">
                            <p class="sr-only">Search</p>
                            <div class="relative">
                                <div class="flex items-center pt-0 pr-0 pb-0 pl-3 absolute inset-y-0 left-0 pointer-events-none">
                                    <p>
                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21
                                        21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </p>
                                </div>
                                <input placeholder="Search" type="search" class="border block pt-2 pr-0 pb-2 pl-10 py-2
                                    border-blue-300 rounded-lg focus:ring-blue-600 focus:border-blue-600 sm:text-sm"
                                    v-model="search"/>
                            </div>
                        </div>
                        <table class="min-w-max w-full table-auto">
                            <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-center">Name</th>
                                    <th class="py-3 px-6 text-center">Medicine</th>
                                    <th class="py-3 px-6 text-center">Quantity</th>
                                    <th class="py-3 px-6 text-center">Date</th>
                                    <!-- <th class="py-3 px-6 text-center">Action</th> -->
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light" >

                                <tr  class="border-b border-gray-200 hover:bg-gray-100" v-for="des in dispenses.data" :key="des.id">
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium">{{ des.name }}</p>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium">{{ des.medicine.name }}</p>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium">{{ des.qty }} pc/s</p>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium">{{ formattedDate(des.created_at)}}</p>
                                        </div>
                                    </td>

                                    <!-- <td class="py-3 px-6 text-center">
                                        <div class="flex item-center justify-center">

                                            <div class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110">
                                                <a href="#" class="btn" @click="edit(des)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="w-4  ml-2 mr-2 transform hover:text-red-500 hover:scale-110">
                                                <a href="#" @click="remove(des)" class="btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </a>
                                                <Modal :show="showConfirm" @close="closeModal">
                                                    <div class="p-4 sm:p-10 text-center overflow-y-auto">

                                                        <span class="mb-4 inline-flex justify-center items-center w-[62px] h-[62px] rounded-full border-4 border-red-50 bg-red-100 text-red-500">
                                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
                                                        </svg>
                                                        </span>


                                                        <h3 class="mb-2 text-2xl font-bold text-gray-800">
                                                            Delete Medicine Category
                                                        </h3>
                                                        <p class="text-gray-500">
                                                            Are you sure you want like to delete this Medicine Category?
                                                        </p>

                                                        <div class="mt-6 flex justify-center gap-x-4">
                                                            <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                                                            <DangerButton @click="deleteCat()">Delete</DangerButton>
                                                        </div>
                                                    </div>
                                                </Modal>
                                            </div>
                                        </div>
                                    </td> -->
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-show="dispenses.data.length < 1" class="flex flex-col w-full mt-9">
                        <h1 class="text-center text-xl text-gray-400 mb-6">No medicine dispenses found</h1>
                        <!-- <img src="../../Components/images/no-result.png" alt="no result" class="w-[250px] opacity-25 mx-auto"> -->
                    </div>

                    <!-- Paginator -->
                    <div class="flex justify-between">
                        <div class="mt-2" v-if="dispenses.data.length > 0">Showing page {{ dispenses.current_page }} of {{ dispenses.last_page }}</div>
                        <Pagination v-if="dispenses.data.length > 0" :links="dispenses.links" class="mt-6"/>
                    </div>
                </div>
            </div>

            </div>
        </div>
    </Sidebar>
</template>
