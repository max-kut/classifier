<template>
    <button :type="nativeType"
            :disabled="disabled || loading"
            :class="{
                [`btn-${type}`]: true,
                'btn-block': block,
                'btn-lg': large,
                'btn-loading': loading
              }"
            class="btn">
        <slot/>
    </button>
</template>

<script>
    export default {
        name: 'VButton',
        props: {
            type: {
                type: String,
                default: 'primary'
            },

            nativeType: {
                type: String,
                default: 'submit'
            },

            loading: {
                type: Boolean,
                default: false
            },

            disabled: {
                type: Boolean,
                default: false
            },

            block: {
                type: Boolean,
                default: false
            },

            large: {
                type: Boolean,
                default: false
            }
        }
    }
</script>

<style lang="scss">
    .btn-loading {
        position: relative;
        pointer-events: none;
        color: transparent !important;

        &:after {
            animation: spinAround 500ms infinite linear;
            border: 2px solid #dbdbdb;
            border-radius: 50%;
            border-right-color: transparent;
            border-top-color: transparent;
            content: "";
            display: block;
            height: 1em;
            width: 1em;
            position: absolute;
            left: calc(50% - (1em / 2));
            top: calc(50% - (1em / 2));
        }
    }

    @keyframes spinAround {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(359deg);
        }
    }
</style>
