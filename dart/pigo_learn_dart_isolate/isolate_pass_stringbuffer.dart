/*
 * isolate 測試傳遞  http request
 */
import 'dart:io';
import 'dart:async';
import 'dart:convert';
import 'dart:isolate';

void main() {
  
  ReceivePort receivePort = new ReceivePort();
  StringBuffer buf = new StringBuffer();
  buf.write("main write\n");
  print("main buf hashCode : " + buf.hashCode.toString() );
  print("old buf : " + buf.toString());
  
  // 把自己的 sendPort 當參數產生個 isolate
  Isolate.spawn(isolatePassStringBuffer, {
    'sendPort': receivePort.sendPort ,
    'buf' : buf
  });

  receivePort.listen((var msg) {
    print("new buf : " + buf.toString());    
  });
}

/**
 * 建立個 isolate function，可以處理 StringBuffer參照 
 */
void isolatePassStringBuffer(var msg) {
  SendPort sendPort = msg['sendPort'];
  StringBuffer buf  = msg['buf'];
  print("isolate buf hashCode : " + buf.hashCode.toString());
  buf.write("isolate write\n");
  print("isolate buf :\n" + buf.toString());
  sendPort.send("ha");
}
