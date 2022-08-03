import { ObjectDirective } from 'vue';

const VButtonLoadingDirective: ObjectDirective = {
    created(el, binding, vnode, prevVnode) {
        vnode.props.disabled = true;
    },
};

export default VButtonLoadingDirective;
