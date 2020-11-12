<template>
    <button :disabled="processing" id="update-inventory-btn"
            @click.prevent="updateInventory()"
            class="btn-link dropdown-item">
        Update Inventory
    </button>
</template>

<script>
    import toast from "../../services/toast";
    import Swal from 'sweetalert2/dist/sweetalert2.js'

    export default {
        name: "Inventory",
        props: ['processing', 'product', 'i'],
        data() {
            return {
            }
        },
        computed: {
        },
        methods: {
            updateInventory(product, i) {
                if (this.processing) {
                    return;
                }

                this.getQuantity().then((result) => {
                    if (result.value) {
                        this.$emit('updating-inventory', true);
                        axios.post(`${baseUrl}products/${this.product.slug}/inventory`, {
                            quantity: result.value.quantity,
                            action_type: result.value.type
                        }).then(response => {
                            if (response.data.data) {
                                this.$emit('inventory-updated', response.data.total_quantity);
                                this.$emit('inventory-updated-data', {
                                    data: response.data.data,
                                    total_quantity: response.data.total_quantity
                                });
                                toast.success(response.data.message);
                            }
                            this.$emit('updating-inventory', false);
                        }).catch(error => {
                            this.$emit('updating-inventory', false);
                        });
                    }
                });
            },

            getQuantity() {
                return Swal.fire({
                    title: 'Update quantity',
                    html: `<small style="line-height: 1.5!important; margin-top: 15px">Enter the quantity and select update type. Only positive numbers are allowed.</small><div class="swal-content-content"><div><input type="number" placeholder="Enter quantity greater than 0" id="inputField" class="filter-input"><select id="typeField" class="filter-input"><option value="stock">Add to stock</option><option value="bought">Record offline sale</option></select></div>` +
                        `<div class="error-area"></div></div>`,
                    preConfirm: () => {
                        return new Promise((resolve, reject) => {
                            const errorArea = $('.error-area');
                            errorArea.html('');
                            const inputField = $('#inputField'),
                                typeField = $('#typeField');
                            const data = {
                                quantity: inputField.val().trim(),
                                type: typeField.val()
                            };

                            const quantity = Math.abs(parseInt(data.quantity.trim()));
                            inputField.val(quantity);

                            if (!quantity) {
                                errorArea.html('<div style="margin-top: 15px; font-weight: bold; text-align: center">Enter a valid quantity!</div>');
                                resolve(false);
                            } else if (!data.type) {
                                errorArea.html('<div style="margin-top: 15px; font-weight: bold; text-align: center">Select transaction type!</div>');
                                resolve(false);
                            } else {
                                resolve(data);
                            }
                        })
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Update Inventory',
                })
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>
