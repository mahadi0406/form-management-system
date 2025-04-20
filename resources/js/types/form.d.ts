export interface FormField {
    type: string;
    name: string;
    label?: string;
    placeholder?: string;
    required?: boolean;
}

interface FormConfiguration {
    title: string;
    method: string;
    action: string;
    configuration: {
        fields: FormField[];
    };
    [key: string]: any;  // Added index signature
}

export interface Form {
    id: number;
    title: string;
    method: string;
    action: string | null;
    configuration: FormConfiguration;
    created_at: string;
    updated_at: string;
}

export interface FormSubmission {
    id: number;
    form_id: number;
    data: Record<string, any>;
    created_at: string;
    updated_at: string;
}
