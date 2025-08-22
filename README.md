# Telephone

Allows for Telephone based functionality within ExpressionEngine.

### Features

#### Field Type

Includes the `tel` FieldType for use within ExpressionEngine Channel Entries. Works with Grid and Fluid fields. Validates the input to ensure it matches the E64 standard. 

##### Outputs link to number
`{FIELD_NAME:tel}`

##### Outputs formatted phone number (XXX) XXX-XXXX
`{FIELD_NAME:e164}`
`{FIELD_NAME:format}`

##### Outputs fully formatted link
`{FIELD_NAME:e164:tel}`

#### Format Template Tag

Allows for ad-hoc formatting of phone numbers outside of Channel Entries. `{exp:tel:format number=""}`

### Requirements

1. ExpressionEngine >= 7.4
2. PHP >= 8.0
3. Extensions Enabled