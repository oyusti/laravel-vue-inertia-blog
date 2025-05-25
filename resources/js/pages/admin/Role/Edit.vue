<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Input} from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { BreadcrumbItem, Role, Permission } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Checkbox } from '@/components/ui/checkbox';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Roles',
        href: '/admin/roles',
    },
    {
        title: 'Editar',
        href: '',
    },
];

const props = defineProps<{
    role: Role;
    permissions: Permission[];
}>();

const form = useForm({
    name: props.role.name,
    permissions: props.role.permissions?.map(permission => permission.id) || [], // Extrae solo los IDs
});

function handlePermissionToggle(permissionId: number, isChecked: boolean) {
    // Obtén una referencia al array actual (¡importante!)
    const currentPermissions = form.permissions; 

    if (isChecked) {
        // Añadir el ID (string) si NO está ya presente
        if (!currentPermissions.includes(permissionId)) {
            // ¡Muta el array directamente! No hagas form.tags = [...], eso rompe la reactividad de useForm
            currentPermissions.push(permissionId); 
        }
    } else {
        // Quitar el ID (string) si está presente
        const index = currentPermissions.indexOf(permissionId);
        if (index > -1) {
            // ¡Muta el array directamente!
            currentPermissions.splice(index, 1); 
        }
    }

};

</script>

<template>
    
<Head title="Roles" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min p-6">
                <form 
                @submit.prevent="form.put(route('admin.roles.update', {role: props.role.id}))"
                class="space-y-4">    
                    <Label>Nombre:</Label>
                    <Input 
                    class="mt-4" 
                    v-model="form.name"
                    placeholder="Ingrese un nombre"></Input>
                    <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
                    <div>
                        <Label>Permisos:</Label>
                        <div v-for="permission in props.permissions" :key="permission.id" class="flex items-center space-x-2 py-1">
                            <Checkbox 
                                :id="'permission-checkbox-' + permission.id"
                                :value="permission.id.toString()"  
                                :checked="form.permissions.includes(permission.id)"
                                @update:checked="(isChecked) => handlePermissionToggle(permission.id, isChecked)"                 
                            />
                            <Label :for="'permission-checkbox-' + permission.id">{{ permission.name }}</Label>
                        </div>
                    </div>

                    <div class="flex justify-end w-full gap-4 mt-4">
                        <Button :disabled="form.processing" class="bg-blue-500">
                            Actualizar
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>