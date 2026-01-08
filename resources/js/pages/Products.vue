<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import {
    Item,
    ItemContent,
    ItemDescription,
    ItemGroup,
    ItemHeader,
    ItemTitle,
} from '@/components/ui/item';
import AppLayout from '@/layouts/AppLayout.vue';
import { store } from '@/routes/products';
import { Head, useForm } from '@inertiajs/vue3';
import { FilePenLine,Trash } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    products: Array<{
        id: number;
        product_name: string;
        product_description: string;
        price: number;
        product_image_url: string | null;
    }>;
}>();

const open = ref(false);

const form = useForm({
    product_name: '',
    product_description: '',
    price: 0,
    product_image_url: null as File | null,
});

const submit = () => {
    form.post(store.url(), {
        onSuccess: () => {
            toast.success('Product created successfully!');
            form.reset();
            open.value = false;
            // Optionally refresh the page to show new product
            window.location.reload();
        },
        onError: () => {
            toast.error('Failed to create product. Please check the form.');
        },
    });
};

const handleFile = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (!target.files || target.files.length === 0) return;

    form.product_image_url = target.files[0];
};


// const deleteProduct = (productId: number) => {
//     // if (!confirm('Are you sure you want to delete this product?')) {
//     //     return;
//     // }

//     store
//         .destroy(productId)
//         .then(() => {
//             toast.success('Product deleted successfully!');
//             // Optionally refresh the page to reflect deletion
//             window.location.reload();
//         })
//         .catch(() => {
//             toast.error('Failed to delete product.');
//         });
// };
</script>

<template>
    <Head title="Products" />
    <AppLayout>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div
                class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center"
            >
                <h1 class="justify-between text-2xl font-semibold">Products</h1>
                <div class="flex items-center gap-2">
                    <Dialog v-model:open="open">
                        <DialogTrigger as-child>
                            <Button>Create</Button>
                        </DialogTrigger>

                        <DialogContent>
                            <DialogHeader>
                                <DialogTitle>Create a new product</DialogTitle>
                                <DialogDescription>
                                    Fill in the details below to create your new
                                    product.
                                </DialogDescription>
                            </DialogHeader>

                            <form @submit.prevent="submit">
                                <div class="grid gap-4 py-4">
                                    <!-- Product Name -->
                                    <div class="grid gap-2">
                                        <label class="text-sm font-medium">
                                            Product Name
                                        </label>
                                        <Input
                                            type="text"
                                            placeholder="Enter product name"
                                            v-model="form.product_name"
                                        />
                                    </div>

                                    <!-- Description -->
                                    <div class="grid gap-2">
                                        <label class="text-sm font-medium">
                                            Description
                                        </label>
                                        <textarea
                                            class="rounded-md border p-2"
                                            rows="4"
                                            placeholder="Enter product description"
                                            v-model="form.product_description"
                                        />
                                    </div>

                                    <!-- Price -->
                                    <div class="grid gap-2">
                                        <label class="text-sm font-medium">
                                            Price
                                        </label>
                                        <Input
                                            type="number"
                                            v-model="form.price"
                                        />
                                    </div>

                                    <!-- Image -->
                                    <div class="grid gap-2">
                                        <label class="text-sm font-medium">
                                            Product Image
                                        </label>
                                        <Input
                                            type="file"
                                            @change="handleFile"
                                        />
                                    </div>
                                </div>

                                <DialogFooter>
                                    <DialogClose as-child>
                                        <Button variant="secondary"
                                            >Cancel</Button
                                        >
                                    </DialogClose>
                                    <Button type="submit"
                                        >Create Product</Button
                                    >
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                </div>
            </div>

            <ItemGroup
                class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 m-3"
            >
                <Item
                    v-for="product in products"
                    :key="product.id"
                    variant="outline"
                    as-child
                    role="listitem"
                >
                    <a
                        href="#"
                        class="block overflow-hidden rounded-lg shadow-sm"
                    >
                        <ItemHeader>
                            <div
                                class="h-44 w-full overflow-hidden bg-slate-100 sm:h-40 md:h-48"
                            >
                                <img :src="product.product_image_url || 'https://via.placeholder.com/150'" :alt="product.product_name" class="h-full w-full object-cover" />

                            </div>
                        </ItemHeader>
                        <ItemContent>
                            <div
                                class="flex h-full flex-col justify-between p-2"
                            >
                                <div>
                                    <ItemTitle class="text-lg">{{
                                        product.product_name
                                    }}</ItemTitle>
                                    <ItemDescription
                                        class="text-sm"
                                    >
                                        {{ product.product_description }}
                                    </ItemDescription>
                                    <p class="mt-2 text-lg font-semibold">
                                        ${{ product.price }}
                                    </p>
                                </div>

                                <!-- Buttons -->
                                <div class="mt-2 flex justify-between gap-2">
                                    <Button
                                    size="sm"
                                        variant="default"
                                        class="flex items-center gap-2"
                                    >
                                        <FilePenLine class="h-4 w-4" /> Update
                                    </Button>
                                    <Button size="sm" variant="destructive">
                                        <Trash class="h-4 w-4" /> Delete
                                    </Button>
                                    
                                </div>
                            </div>
                        </ItemContent>
                    </a>
                </Item>
            </ItemGroup>
        </div>
    </AppLayout>
</template>
