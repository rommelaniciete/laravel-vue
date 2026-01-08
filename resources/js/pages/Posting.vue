<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Posting" />
    <AppLayout>
        <div class="py-10">
            <Dialog>
                <DialogTrigger>
                    <Button>Create</Button>
                </DialogTrigger>
                <DialogContent>
                    <DialogHeader> Create </DialogHeader>
                    <DialogDescription> Form goes here. </DialogDescription>
                    <form>
                        <div class="grid gap-4 py-4">

                            <div class="grid gap-2">
                                <label class="text-sm font-medium">
                                    Post Name
                                </label>
                                <Input
                                    type="text"
                                    placeholder="Enter link name"
                                />
                            </div>

                            <div class="grid gap-2">
                                <label class="text-sm font-medium">
                                    Link
                                </label>
                                <Input placeholder="e.g https://example.com"/>
                            </div>
                        </div>

                        <DialogFooter>
                            <DialogClose as-child>
                                <Button variant="secondary">Cancel</Button>
                            </DialogClose>
                            <Button type="submit">Create Product</Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
        <div class="py-20">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead>Link</TableHead>
                        <TableHead>Created</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow>
                        <TableCell>Facebook</TableCell>
                        <TableCell
                            class="cursor-pointer text-blue-500 underline"
                            ><a href="https://facebook.com"
                                >https://facebook.com</a
                            ></TableCell
                        >
                        <TableCell>2023-01-01</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
