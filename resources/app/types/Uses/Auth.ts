import { Join, PathsToStringProps } from '~/types/Utils/common';
import { Models } from '~/types';

export namespace Auth {
    let languageObject: Partial<Type>;

    export interface Type {
        isLoggedIn: boolean;
        user: Models.User;
    }

    export type StringType = Join<PathsToStringProps<Type>, '.'>
}

export default Auth;
