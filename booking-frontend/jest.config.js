const nextJest = require('next/jest')

const createJestConfig = nextJest({
  // تحديد مسار تطبيق Next.js لتحميل ملفات next.config.js والـ environment variables
  dir: './',
})

const customJestConfig = {
  setupFilesAfterEnv: ['<rootDir>/jest.setup.js'],
  testEnvironment: 'jest-environment-jsdom',
  moduleNameMapper: {
    // التعامل مع الـ Aliases مثل @/components
    '^@/(.*)$': '<rootDir>/$1',
  },
}

module.exports = createJestConfig(customJestConfig)