/*
 * isolate 測試傳遞物件
 */
import 'dart:io';
import 'dart:async';
import 'dart:convert';
import 'dart:isolate';


void main() {
  File file = new File("README.md");
  RandomAccessFile randomFile = file.openSync();
  print("main file hash code" + randomFile.hashCode.toString());
  Isolate.spawn(isolatePassObject,{
    "file": randomFile
  });
  
  print ("main end");
  
}

void isolatePassObject(msg){
  print ("isolatePassObject start");
  var file = msg["file"];

  // File file = new File("README.md");
  // print("file exists : " + file.existsSync().toString());
  print("isolate file hash code" + file.hashCode.toString());
  print ("isolatePassObject end");
}