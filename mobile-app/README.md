# NumNam Mobile App

Flutter mobile application for testing and release against the NumNam platform.

## Setup

1. Install Flutter dependencies:

```bash
flutter pub get
```

1. The app selects its environment file from the `APP_ENV` value passed with `--dart-define`.
Default environment: `production`

Available environment assets:

- `.env.development`
- `.env.staging`
- `.env.production`
- `.env.example` as fallback if the requested environment file is missing

## Run

Development:

```bash
flutter run --dart-define=APP_ENV=development
```

Staging:

```bash
flutter run --dart-define=APP_ENV=staging
```

Production:

```bash
flutter run --dart-define=APP_ENV=production
```

## Build

Android APK:

```bash
flutter build apk --dart-define=APP_ENV=production
```

iOS:

```bash
flutter build ios --dart-define=APP_ENV=production
```

## API

Production API base URL:

```text
https://numnum.pmratnam.com/api/v1
```

Health route:

```text
/api/health
```

Full production health endpoint:

```text
https://numnum.pmratnam.com/api/health
```
