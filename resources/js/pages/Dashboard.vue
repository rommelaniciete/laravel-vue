<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Empty,
    EmptyContent,
    EmptyDescription,
    EmptyHeader,
    EmptyMedia,
    EmptyTitle,
} from '@/components/ui/empty';
import { Skeleton } from '@/components/ui/skeleton';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ArrowUpRightIcon, FolderCode } from 'lucide-vue-next';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- <div class=" space-x-4">
            <Skeleton class="h-12 w-12 rounded-full" />
            <div class="space-y-2">
                <Skeleton class="h-4 w-[250px]" />
                <Skeleton class="h-4 w-[200px]" />
            </div>
        </div> -->
            <Empty>
                <EmptyHeader>
                    <EmptyMedia variant="icon">
                        <FolderCode />
                    </EmptyMedia>
                    <EmptyTitle>No Projects Yet</EmptyTitle>
                    <EmptyDescription>
                        You haven't created any projects yet. Get started by
                        creating your first project.
                    </EmptyDescription>
                     <Skeleton class="h-4 w-[200px]" />
                            <Skeleton class="h-4 w-[100px]" />
                </EmptyHeader>
                <EmptyContent>
                    <div class="flex gap-2">
                        <Button>Create Project</Button>
                        <Button variant="outline"> Import Project </Button>
                    </div>
                </EmptyContent>
                <Button
                    variant="link"
                    as-child
                    class="text-muted-foreground"
                    size="sm"
                >
                    <a href="#"> Learn More <ArrowUpRightIcon /> </a>
                </Button>
            </Empty>
        </div>
    </AppLayout>
</template>
