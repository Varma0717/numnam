# Android Build Configuration

## Signing Configuration

For security reasons, the `key.properties` file containing signing credentials is **NOT** committed to version control.

### Setup Instructions

1. Copy the template file:
   ```bash
   cp key.properties.template key.properties
   ```

2. Edit `key.properties` and replace the placeholder values:
   ```properties
   storePassword=YOUR_ACTUAL_KEYSTORE_PASSWORD
   keyPassword=YOUR_ACTUAL_KEY_PASSWORD
   keyAlias=numnam
   storeFile=numnam-keystore.jks
   ```

3. Ensure the keystore file (`numnam-keystore.jks`) exists in the `android` folder

### Security Notes

- **Never commit** `key.properties` or `numnam-keystore.jks` to version control
- Store credentials securely (password manager, CI/CD secrets, environment variables)
- For CI/CD builds, use environment variables instead of the file

### CI/CD Environment Variables (Optional)

If using automated builds, set these environment variables:
- `NUMNAM_STORE_PASSWORD`
- `NUMNAM_KEY_PASSWORD`
- `NUMNAM_KEY_ALIAS`
- `NUMNAM_STORE_FILE` (path to keystore)
