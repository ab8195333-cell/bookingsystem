const nextJest = require('next/jest')

const createJestConfig = nextJest({
  // مسار تطبيق Next.js لتحميل next.config.js والملفات المكونة تلقائياً
  dir: './',
})

/** @type {import('jest').Config} */
const customJestConfig = {
  setupFilesAfterEnv: ['<rootDir>/jest.setup.js'],
  testEnvironment: 'jest-environment-jsdom',
}

module.exports = createJestConfig(customJestConfig)