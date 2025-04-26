<script setup lang="ts">
import PublicLayout from '@/layouts/app/AppHeaderLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <PublicLayout :breadcrumbs="breadcrumbs">
        <slot />
    </PublicLayout>
</template>
