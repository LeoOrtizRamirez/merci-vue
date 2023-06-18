<template>
    <div class="grid">
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Inversiones</span>
                        <div class="text-900 font-medium text-xl"><span class="text-green-500 font-medium">{{ new Intl.NumberFormat('en-US').format(investment) }}</span></div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-blue-100 border-round" style="width:2.5rem;height:2.5rem">
                        <i class="pi pi-dollar text-blue-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Prestamos</span>
                        <div class="text-900 font-medium text-xl"><span class="text-green-500 font-medium">{{ new Intl.NumberFormat('en-US').format(loans) }}</span></div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-orange-100 border-round" style="width:2.5rem;height:2.5rem">
                        <i class="pi pi-shopping-cart text-orange-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Ganancia</span>
                        <div class="text-900 font-medium text-xl"><span class="text-green-500 font-medium">{{ new Intl.NumberFormat('en-US').format(gain) }}</span></div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-cyan-100 border-round" style="width:2.5rem;height:2.5rem">
                        <i class="pi pi-money-bill text-cyan-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Caja</span>
                        <div class="text-900 font-medium text-xl"><span class="text-green-500 font-medium">{{ new Intl.NumberFormat('en-US').format(current_balance) }}</span></div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-purple-100 border-round" style="width:2.5rem;height:2.5rem">
                        <i class="pi pi-sort-alt text-purple-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'

export default {
    layout: AppLayout,
    props:{
        investment:0,
        loans:0,
        gain:0,
        current_balance:0
    },
};
</script>