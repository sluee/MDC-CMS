<script setup>
    import Sidebar from '@/Layouts/Sidebar.vue';
    import { onMounted, ref } from 'vue';
    import { Link, Head } from '@inertiajs/vue3';
    import { useForm } from '@inertiajs/vue3';

    let props = defineProps({
        doctor: Object, // Doctor and User details
        services: Array
    })

    let form = useForm({
        lastname: props.doctor.user.lastname || '',
        firstname: props.doctor.user.firstname || '',
        suffix: props.doctor.user.ext_name || '',
        middlename:props.doctor.user.middlename || '',
        email: props.doctor.user.email || '',
        contact_no: props.doctor.user.contact_no || '',
        gender: props.doctor.user.gender || '',
        specialization: props.doctor.specialization || '',
        selectedServiceIds: props.doctor.services.map((service) => service.id)


    })



    onMounted(() => {
  const selectRole = document.getElementById('select-role');

  if (selectRole) {
    new TomSelect('#select-role', {
        maxItems: 3,
      });
  }
});

    const submit = () =>{
        form.put('/doctor/'+ props.doctor.id);

    }

</script>

<template>
    <Head title="Update Doctor"/>
    <Sidebar>
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Doctor</h2>
        </template> -->
        <div class="container px-4 py-6 mx-auto">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-x-3">
                        <h2 class="text-3xl font-bold text-black">Edit Doctor: {{ doctor.user.firstname }} {{ doctor.user.lastname }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="w-full mt-10 mx-auto px-4 ">
                <form @submit.prevent="submit">
                    <div class="space-y-6">
                        <div class="block pl-12 font-semibold text-xl self-start text-gray-700">
                            <h1 class="leading-relaxed">Doctor Details Form</h1>
                            <hr>
                          </div>
                      <div class="border-b border-gray-900/10 pb-12">

                        <div class=" px-12 py-2 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-3 mx-auto">
                          <div class="sm:col-span-1">
                            <label for="firstname" class="block text-sm font-medium leading-6 text-gray-900">First name</label>
                            <div class="mt-2">
                              <input type="text" v-model="form.firstname" name="firstname" id="firstname" autocomplete="firstname" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                              <div class="text-sm text-red-500 italic" v-if="form.errors.firstname">{{ form.errors.firstname }}</div>
                            </div>
                          </div>

                          <div class="sm:col-span-1">
                            <label for="lastname" class="block text-sm font-medium leading-6 text-gray-900">Last name</label>
                            <div class="mt-2">
                              <input type="text" v-model="form.lastname" name="lastname" id="lastname" autocomplete="family-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                              <div class="text-sm text-red-500 italic" v-if="form.errors.lastname">{{ form.errors.lastname }}</div>
                            </div>
                          </div>
                          <div class="sm:col-span-1">
                            <label for="middlename" class="block text-sm font-medium leading-6 text-gray-900">Middle name</label>
                            <div class="mt-2">
                              <input type="text" v-model="form.middlename" name="middlename" id="middlename" autocomplete="family-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                              <div class="text-sm text-red-500 italic" v-if="form.errors.middlename">{{ form.errors.middlename }}</div>
                            </div>
                          </div>

                          <div class="sm:col-span-1">
                            <label for="ext_name" class="block text-sm font-medium leading-6 text-gray-900">Extension name</label>
                            <div class="mt-2">
                              <input id="ext_name" v-model="form.ext_name" name="ext_name" type="text" autocomplete="ext_name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                              <div class="text-sm text-red-500 italic" v-if="form.errors.ext_name">{{ form.errors.ext_name }}</div>
                            </div>
                          </div>

                          <div class="m:col-span-1">
                            <label for="gender" class="block text-sm font-medium leading-6 text-gray-900">Gender</label>
                            <div class="mt-2">
                              <select id="gender" v-model="form.gender" name="gender" autocomplete="gender" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                <option selected disabled   >Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                              </select>
                              <div class="text-sm text-red-500 italic" v-if="form.errors.gender">{{ form.errors.gender }}</div>
                            </div>
                          </div>

                          <div class="m:col-span-2">
                            <label for="services" class="block text-sm font-medium leading-6 text-gray-900">Services</label>
                            <div class="mt-2">
                                <!-- <select id="services" v-model="form.selectedServiceIds" name="selectedServiceIds[]" multiple  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 m:max-w-xs sm:text-sm sm:leading-6" >
                                  <option selected disabled >Select services</option>
                                  <option v-for="service in services" :key="service.id" :value="service.id">{{ service.name }}</option>
                                </select> -->
                                <select
                                    id="select-role"
                                    name="selectedServiceIds[]"
                                    multiple
                                    placeholder="Select services..."
                                    autocomplete="off"
                                    v-model="form.selectedServiceIds"
                                    class="block w-full rounded-sm cursor-pointer focus:outline-none"

                                    >
                                    <option selected disabled>Select a service</option>
                                    <option v-for="service in services" :key="service.id" :value="service.id">{{ service.name }}</option>

                                </select>
                                <div class="text-sm text-red-500 italic" v-if="form.errors.services">{{ form.errors.services }}</div>
                              </div>
                          </div>
                          <div class="sm:col-span-2">
                            <label for="contact_no" class="block text-sm font-medium leading-6 text-gray-900">Contact No</label>
                            <div class="mt-2">
                              <input id="contact_no" v-model="form.contact_no" name="contact_no" type="number" autocomplete="contact_no" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                              <div class="text-sm text-red-500 italic" v-if="form.errors.contact_no">{{ form.errors.contact_no }}</div>
                            </div>
                          </div>

                          <div class="sm:col-span-1">
                            <label for="specialization" class="block text-sm font-medium leading-6 text-gray-900">Specialization</label>
                            <div class="mt-2">
                              <input id="specialization" v-model="form.specialization" name="specialization" type="text" autocomplete="email" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                              <div class="text-sm text-red-500 italic" v-if="form.errors.specialization">{{ form.errors.specialization }}</div>
                            </div>
                          </div>


                          <div class="sm:col-span-2">
                            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                            <div class="mt-2">
                              <input id="email" v-model="form.email" name="email" type="email" autocomplete="email" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                              <div class="text-sm text-red-500 italic" v-if="form.errors.email">{{ form.errors.email }}</div>
                            </div>
                          </div>

                          <div class="sm:col-span-1 ">
                            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                            <div class="mt-2">
                              <input type="password" v-model="form.password" name="password" id="password"  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                              <div class="text-sm text-red-500 italic" v-if="form.errors.password">{{ form.errors.password }}</div>
                            </div>
                          </div>


                          <div class="sm:col-span-1">
                            <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900">Confirm Password</label>
                            <div class="mt-2">
                              <input type="password" v-model="form.password_confirmation" name="password_confirmation" id="password_confirmation" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                              <div class="text-sm text-red-500 italic" v-if="form.errors.gender">{{ form.errors.password_confirmation }}</div>
                            </div>
                          </div>

                        </div>
                      </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-6 mb-3">
                      <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                      <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                    </div>
                  </form>
            </div>
        </div>
    </Sidebar>

</template>
