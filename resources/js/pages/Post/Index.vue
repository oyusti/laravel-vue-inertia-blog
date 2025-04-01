<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Post, Message } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Table, TableCaption, TableBody, TableCell, TableHead, TableHeader, TableRow, } from '@/components/ui/table';
import { MessageCircleCode, Pencil, Trash2, Eye } from 'lucide-vue-next';
import { watch } from 'vue';
import { format } from 'date-fns';
import { es } from 'date-fns/locale';

import Swal from 'sweetalert2';
//import PlaceholderPattern from '../../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '',
    },
];

const props = defineProps<{
    posts: Post;
    message?: Message | null;
}>();

/* const page = usePage; */

const deletePost = (id: number) => {
    Swal.fire({
        title: "Esta usted seguro?",
        text: "No pude revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminarlo!",
        }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('admin.posts.destroy', { id }), {
                preserveScroll: true,
                onError: (errors: any) => {
                    console.log(errors);
                }
            });
        }
    });
};

// Watch for changes in the message prop
watch(
    () => props.message,
    (newMessage) => {
        if (newMessage) {
            Swal.fire({
                text: newMessage.text,
                icon: newMessage.icon,
                title: newMessage.title,
            });
        }
    },
    { immediate: true } // Run the callback immediately on component mount
);

// Function to format the date
const formatDate = (dateString: string | null): string => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return format(date, 'dd-MM-yyyy', { locale: es });
};

</script>

<template>
    <Head title="Posts" />
    <AppLayout :breadcrumbs="breadcrumbs">
        {{ console.log(posts) }}
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-end w-full gap-4">
                    <Link :href="route('admin.posts.create')">
                        <Button class="bg-blue-500">
                            Nuevo
                        </Button>
                    </Link>
                </div>

            <!-- <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
            </div> -->
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min p-6">
                <Table>
                    <TableCaption>Lista de Posts.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead >Fecha</TableHead>
                            <TableHead >Titulo</TableHead>
                            <TableHead >Autor</TableHead>
                            <TableHead >Categoria</TableHead>
                            <TableHead >Estatus</TableHead>
                            <TableHead>Acciones</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="post in posts" :key="post.id">
                            <TableCell>{{ formatDate(post.created_at) }}</TableCell>
                            <TableCell>{{ post.title }}</TableCell>
                            <TableCell>{{ post.user.name }}</TableCell>
                            <TableCell>{{ post.category.name }}</TableCell>
                            <TableCell>{{ post.is_published ? 'Publicado' : 'Borrador' }}</TableCell>
                            <TableCell>
                                <div class="flex gap-2">
                                    <Link :href="route('admin.posts.edit', { post: post.id })">
                                        <Button class="bg-green-500">      
                                            <Eye/>
                                        </Button>
                                    </Link>
                                    <Link :href="route('admin.posts.edit', { post: post.id })">
                                        <Button class="bg-blue-500">      
                                            <Pencil/>
                                        </Button>
                                    </Link>
                                    <Button @click="deletePost(post.id)" class="bg-red-500">
                                        <Trash2/>
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>

        <!-- <div class="mt-4">
            {{ posts.links }}
        </div> -->

    </AppLayout>
</template>
